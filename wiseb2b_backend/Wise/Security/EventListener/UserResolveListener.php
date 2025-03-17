<?php

declare(strict_types=1);

namespace Wise\Security\EventListener;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use League\Bundle\OAuth2ServerBundle\Event\UserResolveEvent;

class UserResolveListener
{
    const CLIENT_API_NAME = 'CLIENT_API';

    public function __construct(
        private UserProviderInterface $userProvider,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function onUserResolve(UserResolveEvent $event): void
    {
        try {
            $user = $this->getUserByLoginAndPassword($event);
        } catch (AuthenticationException $exception) {
            return;
        }

        if (!($user instanceof PasswordAuthenticatedUserInterface)) {
            return;
        }

        if ($event->getClient()->getName() !== self::CLIENT_API_NAME && !$this->userPasswordHasher->isPasswordValid($user, $event->getPassword())) {
            return;
        }

        $event->setUser($user);
    }

    /**
     * Pobiera użytkownika na podstawie loginu i hasła.
     * @param UserResolveEvent $event
     * @return UserInterface
     */
    private function getUserByLoginAndPassword(UserResolveEvent $event): ?UserInterface
    {
        return $this->userProvider->loadUserByIdentifier($event->getUsername());
    }
}
