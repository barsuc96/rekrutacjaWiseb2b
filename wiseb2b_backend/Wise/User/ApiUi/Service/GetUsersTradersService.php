<?php

namespace Wise\User\ApiUi\Service;

use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\User\ApiUi\Dto\Users\UsersTradersResponseDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersTradersServiceInterface;
use Wise\User\Service\Trader\Interfaces\ListTradersServiceInterface;

/**
 * Serwis zwraca listę opiekunów/handlowców
 */
class GetUsersTradersService extends AbstractGetService implements GetUsersTradersServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        protected readonly ListTradersServiceInterface $service
    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(InputBag $parameters): array
    {
        $fields = [];
        $filters = [];
        foreach ($parameters->all() as $field => $value) {
            if ($field === 'contentLanguage') {
                continue;
            }

            $filters[] = new QueryFilter($field, $value);
        }
        $filters[] = new QueryFilter('isActive', true);

        $fields = (new UsersTradersResponseDto())->mergeWithMappedFields($fields);
        $params = (new CommonListParams())
            ->setFilters($filters)
            ->setFields($fields);

        $serviceDtoData = ($this->service)($params)->read();

        return $this->shareMethodsHelper->prepareMultipleObjectsResponseDto(
            UsersTradersResponseDto::class,
            $serviceDtoData,
            $fields
        );
    }
}