<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use Exception;
use RuntimeException;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\CommonListResult;
use Wise\Core\Service\ValidatedUserTrait;
use Wise\MultiStore\Service\Interfaces\CurrentStoreServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\Security\Service\Interfaces\ListOverloginUsersForCurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Service\Trader\Interfaces\ListTradersServiceInterface;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;

/**
 * Serwis aplikacji - pobierający listę użytkowników na których zalogowany użytkownik może się przelogować
 */
class ListOverloginUsersForCurrentUserService implements ListOverloginUsersForCurrentUserServiceInterface
{
    use ValidatedUserTrait;

    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly ListUsersServiceInterface $listUsersService,
        private readonly ListTradersServiceInterface $listTradersService,
        private readonly CurrentStoreServiceInterface $currentStoreService
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(CommonListParams $params): CommonListResult
    {
        /**
         * Pobieramy aktualnego użytkownika oraz jego szczegółowe dane
         */
        [$userId, $clientId, $roles] = $this->getValidatedUserData();

        return $this->getOverloginUsersData($userId, $roles, $params);
    }

    protected function getOverloginUsersData(
        int $userId,
        array $roles,
        CommonListParams $params
    ): CommonListResult {
        $newParams = clone $params;

        /**
         * Jeśli zalogowany użytkownik NIE posiada roli ROLE_ADMIN lub ROLE_TRADER,
         * to rzucamy wyjątek o braku dostępu
         */
        if (!in_array(UserRoleEnum::ROLE_ADMIN->value, $roles, true) &&
            !in_array(UserRoleEnum::ROLE_TRADER->value, $roles, true)
        ) {
            $result = new CommonListResult();
            $result->writeAssociativeArray([]);
            return $result;
        }

        /**
         * Jeżeli użytkownik ma role ROLE_TRADER, to dodajemy filtr do użytkowników po trader.id
         */
        if (in_array(UserRoleEnum::ROLE_TRADER->value, $roles, true)) {
            $traderId = $this->getTraderId($userId);

            $userIdFilter = new QueryFilter('traderId', $traderId);
            $newParams->addFilter($userIdFilter);
        }

        // Nie chce pokazywać siebie
        $newParams->addFilter(new QueryFilter('id', [$this->currentUserService->getUserId()], QueryFilter::COMPARATOR_NOT_IN));
        // Pobieramy listę użytkowników
        $serviceDto = ($this->listUsersService)($newParams);
        $serviceDtoData = $serviceDto->read();


        // Weryfikujemy czy użytkownik może zostać zwrócony w konkretnym sklepie
        $currentStoreId = $this->currentStoreService->getCurrentStoreId();
        $serviceDtoData = array_filter($serviceDtoData, function($user) use($currentStoreId) {
            return $user['clientGroupId_storeId'] == null || $user['clientGroupId_storeId'] == $currentStoreId;
        });
        $serviceDtoData = array_values($serviceDtoData);

        // Zwracamy wynik
        $serviceDto->writeAssociativeArray($serviceDtoData);
        return $serviceDto;
    }

    /**
     * Meotda pobiera id tradera po zalogowanym użytkowniku
     */
    protected function getTraderId(int $userId): int
    {
        $params = new CommonListParams();

        $params
            ->setFilters([
                new QueryFilter('userId', $userId)
            ])
            ->setFields(['id'])
        ;

        $traders = ($this->listTradersService)($params)->read();

        return $traders[0]['id'] ?? 0;
    }
}
