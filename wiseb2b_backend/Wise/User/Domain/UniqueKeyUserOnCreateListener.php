<?php

declare(strict_types=1);

namespace Wise\User\Domain;

use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\User\Domain\User\Events\UserHasCreatedEvent;
use Wise\User\Service\User\Exceptions\DuplicateUserLoginException;
use Wise\User\Service\User\GetUserDetailsParams;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;

/**
 * Listener dba o unikalność klucza (email oraz storeId)
 * Upewniamy się, że mamy jeden rekord użytkownika dla konkretnego sklepu
 */
class UniqueKeyUserOnCreateListener
{
    public function __construct(
        private GetUserDetailsServiceInterface $getUserDetailsService,
        private ListUsersServiceInterface $listUsersService
    ) {}

    public function __invoke(UserHasCreatedEvent $event): void
    {
        if($event->getId() === null) {
            return;
        }

        $userData = $this->getUserData($event->getId());

        if(empty($userData)){
            return;
        }

        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('email', $userData['email']),
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
                'email' => 'email',
                'storeId' => 'storeId',
            ])
            ->setExecuteExceptionWhenEntityNotExists(false)
        ;

        return ($this->getUserDetailsService)($params)->read();
    }
}
