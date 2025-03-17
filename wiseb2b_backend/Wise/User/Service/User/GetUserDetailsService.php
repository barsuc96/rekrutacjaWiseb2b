<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractDetailsService;
use Wise\Core\Service\CommonDetailsParams;
use Wise\User\Domain\User\Exceptions\UserNotExistException;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Domain\User\UserServiceInterface;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;

/**
 * Serwis zwracający dane o PROFILU użytkownika
 */
class GetUserDetailsService extends AbstractDetailsService implements GetUserDetailsServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly ?UserAdditionalFieldsService $additionalFieldsService,
        private readonly UserServiceInterface $userService,
    ){
        parent::__construct($repository, $additionalFieldsService);
    }

    /**
     * Zwraca listę joinów dołączonych do zapytania
     * @param CommonDetailsParams $params
     * @param QueryFilter[] $filters
     * @return array
     */
    protected function prepareJoins(CommonDetailsParams $params, array $filters): array
    {
        return $this->userService->prepareJoins($params->getFields());
    }

    /**
     * Wyjątek, gdy encja nie istnieje
     * @param array $entity
     * @param CommonDetailsParams $params
     * @return void
     */
    protected function executeExceptionWhenEntityNotExists(array $entity, CommonDetailsParams $params): void
    {
        throw new UserNotExistException();
    }
}
