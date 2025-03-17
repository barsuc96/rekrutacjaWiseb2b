<?php

namespace Wise\Security\EventSubscriber;

use League\Bundle\OAuth2ServerBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Wise\Security\ApiUi\Service\UserLoginTransformerInterface;
use Wise\Security\Domain\Oauth2\ApiClientRepositoryInterface;
use Wise\User\Domain\User\User;

abstract class AbstractAccessTokenSubscriber
{
    public function __construct(
        private OAuth2Authenticator $authenticator,
        private TokenStorageInterface $tokenStorage,
        private ApiClientRepositoryInterface $apiClientRepository,
        private UserLoginTransformerInterface $userLoginTransformer
    ) {
    }

    /**
     * Pobranie aktualnego tokena sesji
     * @param Passport $passport
     * @return string|null
     */
    protected function getCurrentSessionIdToken(Passport $passport): ?string
    {
        $identifier = $passport->getAttribute('accessTokenId');

        if (is_string($identifier)) {
            return $identifier;
        }

        return null;
    }

    /**
     * Autoryzacja użytkownika
     * @param User $user
     * @param $passport
     * @param string $role
     * @return void
     */
    protected function authorizationUser(User $user, $passport, string $role): void
    {
        $token = new UsernamePasswordToken(
            $this->userLoginTransformer->transform(
                $user,
                $this->getCurrentSessionIdToken($passport),
            ),
            'user-firewall',
            [$role]
        );

        $this->tokenStorage->setToken($token);
    }

    /**
     * Weryfikacja dostępu dla użytkownika
     * @param UserInterface $user
     * @return bool
     */
    protected function isUserHaveAccess(UserInterface $user): bool
    {
        if ($user->getIsActive()) {
            return true;
        }

        return false;
    }
}
