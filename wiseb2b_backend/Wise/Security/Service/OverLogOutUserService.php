<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use RuntimeException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\SessionParamServiceInterface;
use Wise\Security\Service\Events\OverLogOutUserServiceFinished;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\Security\Service\Interfaces\OverLogOutUserServiceInterface;

/**
 * Serwis wylogowujący zalogowanego na innego użytkownika. Przywrócenie sesji do konta początkowego.
 */
class OverLogOutUserService implements OverLogOutUserServiceInterface
{
    public function __construct(
        private readonly SessionParamServiceInterface $sessionParamService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly CurrentUserServiceInterface $currentUserService,
    ) {
    }

    public function __invoke(): CommonServiceDTO
    {
        $sessionParam = $this->sessionParamService->getActiveSessionParam(
            CurrentUserService::OVER_LOGGED_SYMBOL,
        );

        if ($sessionParam === null) {
            throw new RuntimeException('Błąd: aktualnie nie zalogowany na innego użytkownika.');
        }

        $this->sessionParamService->deactivateSessionParam(CurrentUserService::OVER_LOGGED_SYMBOL);
        $this->eventDispatcher->dispatch(new OverLogOutUserServiceFinished(fromUserId: intval($sessionParam->getValue()), toUserId: $this->currentUserService->getUserId()), OverLogOutUserServiceFinished::getName());

        return new CommonServiceDTO();
    }
}
