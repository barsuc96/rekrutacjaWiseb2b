<?php

declare(strict_types=1);

namespace Wise\Security\Domain;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserPasswordHasher implements UserPasswordHasherInterface
{
    public function __construct(
        private PasswordHasherFactoryInterface $hasherFactory
    ) {
    }

    /**
     * @param PasswordAuthenticatedUserInterface $user
     */
    public function hashPassword($user, string $plainPassword): string
    {
        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            if (!$user instanceof UserInterface) {
                throw new \TypeError(sprintf('Expected an instance of "%s" as first argument, but got "%s".', UserInterface::class, get_debug_type($user)));
            }
            trigger_deprecation('symfony/password-hasher', '5.3', 'The "%s()" method expects a "%s" instance as first argument. Not implementing it in class "%s" is deprecated.', __METHOD__, PasswordAuthenticatedUserInterface::class, get_debug_type($user));
        }

        $salt = null;
        // TODO: Co robimy z Salt? W poniższym kodzie zawsze jest nullem. Poza tym nie ma sensu tego przechowywać w bazie danych
        if ($user instanceof LegacyPasswordAuthenticatedUserInterface) {
            $salt = $user->getSalt();
        } elseif ($user instanceof UserInterface) {
            $salt = method_exists($user, 'getSalt') ? $user->getSalt() : null;

            if ($salt) {
                trigger_deprecation('symfony/password-hasher', '5.3', 'Returning a string from "getSalt()" without implementing the "%s" interface is deprecated, the "%s" class should implement it.', LegacyPasswordAuthenticatedUserInterface::class, get_debug_type($user));
            }
        }

        $hasher = $this->hasherFactory->getPasswordHasher($user);

        return $hasher->hash($plainPassword, $salt);
    }

    /**
     * @param PasswordAuthenticatedUserInterface $user
     */
    public function isPasswordValid($user, string $plainPassword): bool
    {
        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            if (!$user instanceof UserInterface) {
                throw new \TypeError(sprintf('Expected an instance of "%s" as first argument, but got "%s".', UserInterface::class, get_debug_type($user)));
            }
            trigger_deprecation('symfony/password-hasher', '5.3', 'The "%s()" method expects a "%s" instance as first argument. Not implementing it in class "%s" is deprecated.', __METHOD__, PasswordAuthenticatedUserInterface::class, get_debug_type($user));
        }

        $salt = null;

        if ($user instanceof LegacyPasswordAuthenticatedUserInterface) {
            $salt = $user->getSalt();
        } elseif ($user instanceof UserInterface) {
            $salt = $user->getSalt();

            if (null !== $salt) {
                trigger_deprecation('symfony/password-hasher', '5.3', 'Returning a string from "getSalt()" without implementing the "%s" interface is deprecated, the "%s" class should implement it.', LegacyPasswordAuthenticatedUserInterface::class, get_debug_type($user));
            }
        }

        if (null === $user->getPassword()) {
            return false;
        }

        $hasher = $this->hasherFactory->getPasswordHasher($user);

        return $hasher->verify($user->getPassword(), $plainPassword, $salt);
    }

    /**
     * @param PasswordAuthenticatedUserInterface $user
     */
    public function needsRehash($user): bool
    {
        if (null === $user->getPassword()) {
            return false;
        }

        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            if (!$user instanceof UserInterface) {
                throw new \TypeError(sprintf('Expected an instance of "%s" as first argument, but got "%s".', UserInterface::class, get_debug_type($user)));
            }
            trigger_deprecation('symfony/password-hasher', '5.3', 'The "%s()" method expects a "%s" instance as first argument. Not implementing it in class "%s" is deprecated.', __METHOD__, PasswordAuthenticatedUserInterface::class, get_debug_type($user));
        }

        $hasher = $this->hasherFactory->getPasswordHasher($user);

        return $hasher->needsRehash($user->getPassword());
    }
}
