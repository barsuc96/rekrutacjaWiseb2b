<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\Traders;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\User\ApiAdmin\Dto\Traders\GetTraderResponseDto;
use Wise\User\ApiAdmin\Service\Traders\Interfaces\GetTradersServiceInterface;
use Wise\User\Service\Trader\Interfaces\ListByFiltersTraderServiceInterface;

class GetTradersService extends AbstractGetService implements GetTradersServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListByFiltersTraderServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [];
        $joins = [];

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'id') {
                $field = 't0.idExternal';
            }
            if ($field === 'internalId') {
                $field = 't0.id';
            }

            $filters[] = new QueryFilter($field, $value);
        }

        $fields = [
            'id' => 't0.idExternal',
            'internalId' => 't0.id',
        ];

        $fields = (new GetTraderResponseDto())->mergeWithMappedFields($fields);

        $serviceDtoData = ($this->service)($filters, $joins, $fields)->read();

        return (new GetTraderResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }
}
