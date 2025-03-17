<?php

namespace Wise\User\Service\Trader;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Service\Trader\Interfaces\GetTraderDetailsServiceInterface;
use Wise\User\Service\Trader\Interfaces\GetTraderForCurrentUserServiceInterface;
use Wise\User\Service\User\GetUserDetailsParams;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;

/**
 * Serwis zwraca Handlowca dla obecnie zalogowanego użytkownika
 */
class GetTraderForCurrentUserService implements GetTraderForCurrentUserServiceInterface
{
    public function __construct(
        protected readonly GetTraderDetailsServiceInterface $service,
        protected readonly CurrentUserServiceInterface $currentUserService,
        protected readonly GetUserDetailsServiceInterface $userDetailsService,

    ){}

    public function __invoke(): CommonServiceDTO
    {
        $userDetails = $this->getUserDetails();

        if ($userDetails['traderId'] === null) {
            throw new ObjectNotFoundException('Użytkownik nie posiada przypisanego handlowca');
        }

        $traderDetails = $this->getTraderDetails($userDetails);

        // Jeśli opiekun jest nieaktywny bądź nie istnieje
        if (empty($traderDetails) || $traderDetails['isActive'] !== true) {
            throw new ObjectNotFoundException('Handlowiec jest nieaktywny bądź nie istnieje');
        }

        $resultServiceDto = new CommonServiceDTO();
        $resultServiceDto->writeAssociativeArray($traderDetails);

        return $resultServiceDto;
    }

    /**
     * Zwraca szczegóły zalogowanego użytkownika
     * @return array
     */
    protected function getUserDetails(): array
    {
        $params = (new GetUserDetailsParams())
            ->setUserId($this->currentUserService->getUserId())
            ->setFields([]);

        return ($this->userDetailsService)($params)->read();
    }

    /**
     * Zwraca szczegóły opiekuna/handlowca
     * @return array
     */
    protected function getTraderDetails(array $userDetails): array
    {
        $params = (new GetTraderDetailsParams())
            ->setTraderId($userDetails['traderId']);

        return ($this->service)($params)->read();
    }
}