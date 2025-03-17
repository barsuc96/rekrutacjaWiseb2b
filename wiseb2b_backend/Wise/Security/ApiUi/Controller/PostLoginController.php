<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Controller;

use JetBrains\PhpStorm\Pure;
use Nelmio\ApiDocBundle\Annotation\Security as ApiSecurity;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum as ApiResponseStatusEnum;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\ApiUi\Dto\Common200FormResponseDto;
use Wise\Core\ApiUi\Helper\ResponsePostHelper;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Core\Exception\CommonLogicException;
use Wise\Security\ApiUi\Dto\LoginDto;
use Wise\Security\ApiUi\Service\Interfaces\PostLoginServiceInterface;
use Wise\Security\Service\Interfaces\CanLoginServiceInterface;
use Wise\Security\Service\Interfaces\SetOauthApiClientIdServiceInterface;
use Wise\Security\WiseSecurityExtension;
use Wise\User\Service\User\Interfaces\AddUserLoginHistoryServiceInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class PostLoginController extends UiApiBaseController
{
    #[Pure]
    public function __construct(
        Security $security,
        private readonly AddUserLoginHistoryServiceInterface $addUserLoginHistoryService,
        private readonly PostLoginServiceInterface $service,
        private readonly ResponsePostHelper $responsePostHelper,
        private readonly SetOauthApiClientIdServiceInterface $setOauthApiClientIdService,
        private readonly CanLoginServiceInterface $canLoginService,
        private readonly TranslatorInterface $translator
    ) {
        parent::__construct(
            $security,
        );
    }

    #[Route(
        path: 'auth/login',
        methods: ['POST'],
    )]
    #[OA\Tag(name: 'Auth')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/LoginDto", type: "object")
    )]
    #[OA\Response(
        ref: "#/components/schemas/TokenResponseDto",
        response: Response::HTTP_OK
    )]
    #[ApiSecurity(name: null)]
    public function postLoginAction(Request $request): JsonResponse
    {
        // Walidacja danych (za pomocą serwisu) przed autoryzacją
        if($this->validate($request) === false){
            return $this->responsePostHelper->prepareAuthorizationFailedResponse();
        }

        // Autoryzacja oAuth
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $data['grant_type'] = "password";
        $data['username'] = strtolower($data['username']);

        /** @var Response $response */
        $response = $this->container
            ->get('http_kernel')
            ->handle($this->prepareOAuthTokenRequest($data), HttpKernelInterface::SUB_REQUEST);

        if ($response->getStatusCode() !== Response::HTTP_OK ){
            return $this->responsePostHelper->prepareAuthorizationFailedResponse();
        }

        /**
         * Zapisujemy w historii, że użytkownik poprawnie się zalogował
         */
        if ($response->getStatusCode() === Response::HTTP_OK) {

            try{
                // Walidacja czy użytkownik może się zalogować po autoryzacji przez oAuth
                $this->canLoginService->validateAfterLogin($data['username']);

                ($serviceDTO = new CommonModifyParams())
                    ->writeAssociativeArray([
                        'userId.login' => strtolower($data['username']),
                        'ip' => $request->getClientIp(),
                    ]);

                ($this->addUserLoginHistoryService)($serviceDTO);
                ($this->setOauthApiClientIdService)($data['client_id']);

            // Obsługa exceptiona CommonLogicException
            }catch (CommonLogicException $exception){

                $exceptionMessage = !empty($exception->getMessageException()) ? $exception->getMessageException() : null;
                $translationMessage = !empty($exception->getTranslationKey()) ? $this->translator->trans($exception->getTranslationKey(), $exception->getTranslationParams()): null;

                $message = $exceptionMessage ?? $translationMessage ?? $exception->getMessage();

                if (!empty($exception?->getResponseMessage())) {
                    $message .= $exception->getResponseMessage();
                }

                return (new Common200FormResponseDto(
                    status: ApiResponseStatusEnum::SUCCESS->value,
                    message: $message,
                    messageStyle: ResponseMessageStyle::FAILED->value,
                    showMessage: true,
                    showModal: false
                ))->setFieldsInfo([])->setData([])->jsonSerialize();
            }
        }

        return new JsonResponse($response->getContent(), json: true);
    }

    private function prepareOAuthTokenRequest(array $payload): Request
    {
        $request = new Request();
        $request->attributes->set('_controller', ['league.oauth2_server.controller.token', 'indexAction']);
        $request->server->add([
            'REQUEST_METHOD' => Request::METHOD_POST,
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => '80',
            'HTTPS' => 'off',
            'REQUEST_URI' => '/token',
        ]);
        $request->headers->add(['content-type' => 'application/x-www-form-urlencoded']);
        $request->request->add($payload);

        // Inicjalizacja sesji i przypisanie jej do requestu
        $session = new Session(new NativeSessionStorage());
        $request->setSession($session);

        $request->getSession()->set(WiseSecurityExtension::OAUTH_API_CLIENT_ID_SESSION_PARAM, $payload['client_id']);

        return $request;
    }

    /**
     * Weryfikacja czy można zalogować się na konto użytkownika
     * @param Request $request
     * @return bool
     * @throws \Wise\Core\Exception\InvalidInputDataException
     */
    protected function validate(Request $request): bool
    {
        $result = $this->service->process($request->getContent(), LoginDto::class);
        $resultContentArray = json_decode($result->getContent(), true);

        return $resultContentArray['data']['result'];
    }
}
