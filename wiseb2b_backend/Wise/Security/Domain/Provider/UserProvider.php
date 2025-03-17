<?php

declare(strict_types=1);

namespace Wise\Security\Domain\Provider;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Wise\MultiStore\Service\Interfaces\CurrentStoreServiceInterface;
use Wise\Security\ApiUi\Model\UserLoginInfo;
use Wise\User\Domain\User\UserRepositoryInterface;
use Doctrine\ORM\NonUniqueResultException;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CurrentStoreServiceInterface $currentStoreService
    ) {
    }

    /**
     * @throws NonUniqueResultException
     * @deprecated since Symfony 5.3, use loadUserByIdentifier() instead
     */
    public function loadUserByUsername(string $username): ?UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->userRepository->findByUsernameOrEmail(username: $identifier, storeId: $this->currentStoreService->getCurrentStoreId());
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$this->supportsClass($user::class)) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return UserLoginInfo::class === $class;
    }

    /**
     * Upgrades the hashed password of a user, typically for using a better hash algorithm.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {

    }
}
