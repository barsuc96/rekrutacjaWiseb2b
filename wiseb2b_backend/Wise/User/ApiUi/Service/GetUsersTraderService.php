<?php

namespace Wise\User\ApiUi\Service;

use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsService;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\ApiUi\Dto\Users\UsersTradersResponseDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersTraderServiceInterface;
use Wise\User\Service\Trader\Interfaces\GetTraderForCurrentUserServiceInterface;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;

/**
 * Serwis zwraca opiekuna/handlowca przypisanego do użytkownika
 */
class GetUsersTraderService extends AbstractGetDetailsService implements GetUsersTraderServiceInterface
{
    public function __construct(
        protected readonly UiApiShareMethodsHelper $shareMethodsHelper,
        protected readonly GetTraderForCurrentUserServiceInterface $service,
        protected readonly CurrentUserServiceInterface $currentUserService,
        protected readonly GetUserDetailsServiceInterface $userDetailsService,

    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(InputBag $parameters): array
    {

        try {
            $traderDetails = ($this->service)()->read();
        } catch (ObjectNotFoundException $e) {
            return [];
        }

        // Zwrócenie wyniku końcowego
        $fields = (new UsersTradersResponseDto())->mergeWithMappedFields([]);
        $responseDto = $this->shareMethodsHelper->prepareSingleObjectResponseDto(
            UsersTradersResponseDto::class,
            $traderDetails,
            $fields
        );

        return $responseDto->resolveArrayData();
    }
}