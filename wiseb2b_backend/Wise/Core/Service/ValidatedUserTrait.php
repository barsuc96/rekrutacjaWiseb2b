<?php

declare(strict_types=1);

namespace Wise\Core\Service;

use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;

/**
 * Trait do pobierania danych aktualnego uzytkownika
 * @deprecated
 */
trait ValidatedUserTrait
{
    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
    ) {}

    /**
     * Metoda sprawdza czy $userId istnieje,
     * jeśli nie to pobieramy go z aktualnego użytkowwnika
     */
    protected function getValidatedUserData(?int $userId = null): ?array
    {
        $roles = [];
        $clientId = null;

        if ($userId === null) {
            $loggedUser = $this->currentUserService->getCurrentUser();

            $userId = $loggedUser->getId();
            $clientId = $loggedUser->getClientId();
            $roles = $loggedUser->getRoles();
        }

        return [$userId, $clientId, $roles];
    }
}
