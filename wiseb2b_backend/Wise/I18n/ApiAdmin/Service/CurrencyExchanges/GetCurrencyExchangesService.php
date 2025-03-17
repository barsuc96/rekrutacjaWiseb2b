<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Service\CurrencyExchanges;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\I18n\ApiAdmin\Dto\CurrencyExchanges\GetCurrencyExchangeResponseDto;
use Wise\I18n\ApiAdmin\Service\CurrencyExchanges\Interfaces\GetCurrencyExchangesServiceInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\ListByFiltersCurrencyExchangeServiceInterface;

class GetCurrencyExchangesService extends AbstractGetService implements GetCurrencyExchangesServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListByFiltersCurrencyExchangeServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [];
        $joins = [];

        $fields = [
            'id' => 't0.idExternal',
            'internalId' => 't0.id',
        ];

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'id') {
                $field = 't0.idExternal';
            }
            if ($field === 'internalId') {
                $field = 't0.id';
            }

            $filters[] = new QueryFilter($field, $value);
        }

        $fields = (new GetCurrencyExchangeResponseDto())->mergeWithMappedFields($fields);

        $serviceDtoData = ($this->service)($filters, $joins, $fields)->read();

        return (new GetCurrencyExchangeResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }
}
