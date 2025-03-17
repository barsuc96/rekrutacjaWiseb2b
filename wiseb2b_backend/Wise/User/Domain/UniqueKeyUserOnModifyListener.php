<?php

declare(strict_types=1);

namespace Wise\User\Domain;

use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\User\Domain\User\Events\UserHasChangedEvent;
use Wise\User\Service\User\Exceptions\DuplicateUserLoginException;
use Wise\User\Service\User\GetUserDetailsParams;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;

/**
 * Listener dba o unikalność klucza (login oraz storeId)
 * Upewniamy się, że mamy jeden rekord użytkownika dla konkretnego sklepu
 */
class UniqueKeyUserOnModifyListener
{
    public function __construct(
        private GetUserDetailsServiceInterface $getUserDetailsService,
        private ListUsersServiceInterface $listUsersService
    ) {}

    public function __invoke(UserHasChangedEvent $event): void
    {
        $userData = $this->getUserData($event->getId());

        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('login', $userData['login']),
                new QueryFilter('storeId', $userData['storeId']),
            ])
            ->setFields(['id']);

        $users = ($this->listUsersService)($params)->read();

        if (count($users) > 1) {
            throw new DuplicateUserLoginException();
        }
    }

    /**
     * Zwraca dane użytkownika
     * @param int $userId
     * @return array
     */
    protected function getUserData(int $userId): array
    {
        $params = new GetUserDetailsParams();
        $params
            ->setUserId($userId)
            ->setFields([
                'id' => 'id',
                'login' => 'login',
                'storeId' => 'storeId',
            ]);

        return ($this->getUserDetailsService)($params)->read();
    }

}
