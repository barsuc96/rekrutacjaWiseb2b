<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Service\Countries;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\I18n\ApiAdmin\Dto\Countries\GetCountryResponseDto;
use Wise\I18n\ApiAdmin\Service\Countries\Interfaces\GetCountriesServiceInterface;
use Wise\I18n\Service\Country\Interfaces\ListByFiltersCountryServiceInterface;

class GetCountriesService extends AbstractGetService implements GetCountriesServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListByFiltersCountryServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [];
        $joins = [];

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'id') {
                $field = 'idExternal';
            }

            if ($field === 'internalId') {
                $field = 'id';
            }

            $filters[] = new QueryFilter($field, $value);
        }

        $fields = [
            'id' => 'idExternal',
            'internalId' => 'id'
        ];

        $fields = (new GetCountryResponseDto())->mergeWithMappedFields($fields);

        $serviceDtoData = ($this->service)($filters, $joins, $fields)->read();

        return (new GetCountryResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }
}
