<?php

declare(strict_types=1);

namespace Wise\Service\ApiAdmin\Service\Services;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Service\ApiAdmin\Dto\Services\GetServiceResponseDto;
use Wise\Service\ApiAdmin\Service\Services\Interfaces\GetServicesServiceInterface;
use Wise\Service\Service\Service\Interfaces\ListByFiltersServiceServiceInterface;

class GetServicesService extends AbstractGetService implements GetServicesServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListByFiltersServiceServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [];
        $joins = [];

        $fields = [
            'id' => 'idExternal',
            'internalId' => 'id',
        ];

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'id') {
                $field = 'idExternal';
            }
            if ($field === 'internalId') {
                $field = 'id';
            }

            $filters[] = new QueryFilter($field, $value);
        }

        $fields = (new GetServiceResponseDto())->mergeWithMappedFields($fields);

        $serviceDtoData = ($this->service)($filters, $joins, $fields)->read();

        return (new GetServiceResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }
}
