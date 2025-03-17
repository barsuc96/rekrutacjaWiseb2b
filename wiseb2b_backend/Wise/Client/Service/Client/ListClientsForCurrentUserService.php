<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Exception;
use Wise\Client\Service\Client\Interfaces\ListClientsForCurrentUserServiceInterface;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;
use Wise\Core\Service\ValidatedUserTrait;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;

class ListClientsForCurrentUserService implements ListClientsForCurrentUserServiceInterface
{
    use ValidatedUserTrait;

    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly ListClientsServiceInterface $listClientsService,
    ) {
    }

    /**
     * @param CommonListParams $params
     * @return CommonListResult
     * @throws Exception
     */
    public function __invoke(CommonListParams $params): CommonListResult
    {
        /**
         * Pobieramy dane aktualnego użytkownika
         */
        [$userId, $clientId, $roles] = $this->getValidatedUserData();

        return $this->getClientsData($clientId, $roles, $params);
    }

    /**
     * Metoda ma za zadanie pobranie listy klientów, wraz z dodatkowymi polami offersCount i ordersCount.
     * na podstawię podanego $clientId oraz $roleId na podstawie roli zalogowanego użytkownika biznesowego
     *
     * 3 - jeśli zalogowany użytkownik NIE ma roli 1, // ROLE_ADMIN
     *      nie dodajemy filtrowania i zwracamy wszystkie, niezależenie od clientId
     * 1 - jeśli zalogowany użytkownik NIE ma roli 1, // ROLE_USER_MAIN
     *      dodajemy filtr po clientId, pobranego z zalogowanego użytkownika
     * 2 - jeśli zalogowany użytkownik ma role 2 // ROLE_USER
     *      zwracamy pustą tablicę
     */
    protected function getClientsData(
        int $clientId,
        array $roles,
        CommonListParams $params
    ): CommonListResult {
        $newParams = clone $params;

        /**
         * Jeśli zalogowany użytkownik NIE posiada roli ROLE_ADMIN lub ROLE_USER_MAIN,
         * to zwracamy pustą tablicę
         */
        if (!in_array(UserRoleEnum::ROLE_ADMIN->value, $roles, true) &&
            !in_array(UserRoleEnum::ROLE_USER_MAIN->value, $roles, true)
        ) {
            ($resultDTO = new CommonListResult())->writeAssociativeArray([]);
            $resultDTO->setTotalCount(0);
            return $resultDTO;
        }
        /**
         * Jeżeli użytkownik jest adminem klienta, to dodajemy filtr na id
         */
        if (in_array(UserRoleEnum::ROLE_USER_MAIN->value, $roles, true)) {
            $clientIdFilter = new QueryFilter('id', $clientId);
            $newParams->addFilter($clientIdFilter);
        }

        return ($this->listClientsService)($newParams);
    }
}
