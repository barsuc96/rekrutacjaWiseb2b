<?php

declare(strict_types=1);

namespace Wise\Security\EventSubscriber;

use DateTime;
use JetBrains\PhpStorm\ArrayShape;
use League\Bundle\OAuth2ServerBundle\Security\Authenticator\OAuth2Authenticator;
use League\Bundle\OAuth2ServerBundle\Security\Exception\OAuth2AuthenticationFailedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Wise\ClientApi\ApiClient\Abstract\Controller\ClientApiBaseController;
use Wise\ClientApi\ApiClient\Controller\Core\PostAccessTokenController;
use Wise\Core\ApiAdmin\Controller\AbstractAdminApiController;
use Wise\Core\ApiAdmin\Controller\AdminApiBaseController;
use Wise\Core\ApiUi\Controller\Core\AbstractUiApiController;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\MultiStore\Domain\Store\Service\Interfaces\StoreServiceInterface;
use Wise\Payment\ApiPublic\Controller\OnlinePayment\PostAuthorizeOnlinePaymentsController;
use Wise\Security\ApiAdmin\Controller\Oauth2\PostTokenController;
use Wise\Security\ApiUi\Controller\PostLoginController;
use Wise\Security\ApiUi\Controller\PostPasswordForgotController;
use Wise\Security\ApiUi\Controller\PostPasswordResetController;
use Wise\Security\ApiUi\Service\UserLoginTransformerInterface;
use Wise\Security\Domain\Oauth2\ApiClient;
use Wise\Security\Domain\Oauth2\ApiClientRepositoryInterface;
use Wise\Security\Exception\AuthenticationException;
use Wise\Security\Exception\InvalidTokenException;
use Wise\Security\Service\Interfaces\SetOauthApiClientIdServiceInterface;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRoleEnum;
use function Psl\File\read;

/**
 * Zarządzanie uwierzytelnieniem dostępu do API
 */
class AccessTokenSubscriber extends AbstractAccessTokenSubscriber implements EventSubscriberInterface
{
    private const ADMIN_API_NOT_AUTHENTICATE_CONTROLLER = [
        PostTokenController::class,
        PostAuthorizeOnlinePaymentsController::class,
    ];
    private const CLIENT_API_NOT_AUTHENTICATE_CONTROLLER = [
        PostAccessTokenController::class
    ];

    private const UI_API_NOT_AUTHENTICATE_CONTROLLER = [
        PostLoginController::class,
        PostPasswordForgotController::class,
        PostPasswordResetController::class,
    ];

    public function __construct(
        private OAuth2Authenticator $authenticator,
        private TokenStorageInterface $tokenStorage,
        private ApiClientRepositoryInterface $apiClientRepository,
        private UserLoginTransformerInterface $userLoginTransformer,
        private SupportAdditionalAccessTokenProviderService $additionalAccessTokenProviderService,
        private readonly StoreServiceInterface $storeService,
        private readonly SetOauthApiClientIdServiceInterface $setOauthApiClientIdService
    ) {
        parent::__construct($authenticator, $tokenStorage, $apiClientRepository, $userLoginTransformer);
    }

    #[ArrayShape([KernelEvents::CONTROLLER => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * Zarządzanie uwierzytelnieniem dostępu do API
     * @throws AuthenticationException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        $currentController = $controller[0];

        // Autoryzacja w zależności od typu kontrolera
        if ($currentController instanceof AdminApiBaseController || $currentController instanceof AbstractAdminApiController) {
            $this->adminApiAuthenticate($event, $currentController);
        } elseif ($currentController instanceof UiApiBaseController || $currentController instanceof AbstractUiApiController) {
            $this->uiApiAuthenticate($event, $currentController);
        }else{
            $this->additionalAccessTokenProviderService->supportAdditionalAccessTokenProvider($event, $currentController);
        }
    }

    /**
     * Autoryzacja dla UI API
     * @throws AuthenticationException
     */
    private function uiApiAuthenticate(ControllerEvent $event, $currentController): void
    {
        if ($this->isSkipUiApiController($currentController)) {
            return;
        }

        $request = $event->getRequest();

        try {
            $passport = $this->authenticator->doAuthenticate($request);
        } catch (OAuth2AuthenticationFailedException $exception) {
            $this->tokenStorage->setToken(new NullToken());
            throw new InvalidTokenException('Invalid token.');
        }

        ($this->setOauthApiClientIdService)($passport->getAttribute('oauthClientId'));

        /** @var User $user */
        $user = $passport->getUser();

        // Pobieram listę dostępnych sklepów dla klienta
        $stores = $this->storeService->getStoresByClientId($user->getClientId());

        if($stores !== null){
           //TODO: Weryfikacja dostępu do sklepu jeśli nie jest równe null+
        }

        if ($this->isUserHaveAccess($user) === false) {
            throw new AuthenticationException('Access denied.');
        }

        $this->authorizationUser($user, $passport, UserRoleEnum::ROLE_USER->name);
    }

    /**
     * Autoryzacja dla Admin API
     * @throws AuthenticationException
     */
    private function adminApiAuthenticate(ControllerEvent $event, $currentController): void
    {
        if ($this->isSkipAdminApiController($currentController)) {
            return;
        }

        $request = $event->getRequest();

        try {
            $passport = $this->authenticator->doAuthenticate($request);
        } catch (OAuth2AuthenticationFailedException $exception) {
            throw new InvalidTokenException('Invalid token.');
        }

        $client = $this->apiClientRepository->findOneBy([
            'identifier' => $passport->getAttribute('oauthClientId')
        ]);

        ($this->setOauthApiClientIdService)($passport->getAttribute('oauthClientId'));

        if ($this->isClientHaveAccess($client, $currentController) === false) {
            throw new AuthenticationException('Access denied.');
        }
    }

    /**
     * Weryfikacja czy pominąć autoryzację dla Admin API
     * @param $currentController
     * @return bool
     */
    private function isSkipAdminApiController($currentController): bool
    {
        if (in_array($currentController::class, self::ADMIN_API_NOT_AUTHENTICATE_CONTROLLER)) {
            return true;
        }

        return false;
    }

    /**
     * Weryfikacja czy pominąć autoryzację dla UI API
     * @param $currentController
     * @return bool
     */
    private function isSkipUiApiController($currentController): bool
    {
        if (in_array($currentController::class, self::UI_API_NOT_AUTHENTICATE_CONTROLLER)) {
            return true;
        }

        return false;
    }


    /**
     * Weryfikacja dostępu do API
     * @param ApiClient $client
     * @param $currentController
     * @return bool
     */
    private function isClientHaveAccess(ApiClient $client, $currentController): bool
    {
        if (
            $client->isActive()
            && ($client->getExpirationDate() >= (new DateTime('NOW')))
            && $this->checkCommonScopesExists($client->getApiScopes(), $currentController->getRequiredApiScopes())
        ) {
            return true;
        }

        return false;
    }

    private function checkCommonScopesExists($ClientScopes, $controllerScopes): bool
    {
        $clientApiScopes = [];
        foreach ($ClientScopes as $scope) {
            $clientApiScopes[] = $scope->getName();
        }

        if (array_intersect($clientApiScopes, $controllerScopes)) {
            return true;
        }
        return false;
    }

    /**
     * Weryfikacja dostępu na podstawie Scope (uprawnień do czynności)
     * @param ApiClient $authorizationClient - Jest to klient Autoryzacyjny (oAuth2)
     * @param ClientApiBaseController $controller
     * @return bool
     */
    private function checkCommonScopesExistsForClientApi(
        ApiClient $authorizationClient,
        ClientApiBaseController $controller
    ): bool {
        $hasScopeClientApi = false;

        // Sprawdzenie, czy klient autoryzacyjny ma uprawnienia do użycia kontrolera ClientApi
        foreach ($authorizationClient->getScopes() as $scope) {
            if ($scope->__toString() == $controller->getScope()) {
                $hasScopeClientApi = true;
                break;
            }
        }

        // Sprawdzanie, czy klient autoryzacyjny ma uprawnienia do wykonywania czynności, które umożliwia kontroler ClientApi
        $clientApiScopes = [];
        foreach ($authorizationClient->getApiScopes() as $scope) {
            $clientApiScopes[] = $scope->getName();
        }


        if (
            (empty($clientApiScopes) && empty($controller->getRequiredApiScopes()) || !empty(array_intersect($clientApiScopes,
                    $controller->getRequiredApiScopes()))) &&
            $hasScopeClientApi
        ) {
            return true;
        }

        return false;
    }

}
