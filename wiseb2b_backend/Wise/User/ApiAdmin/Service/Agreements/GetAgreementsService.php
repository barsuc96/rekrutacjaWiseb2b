<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\Agreements;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\User\ApiAdmin\Dto\Agreements\GetAgreementResponseDto;
use Wise\User\ApiAdmin\Service\Agreements\Interfaces\GetAgreementsServiceInterface;
use Wise\User\Service\Agreement\Interfaces\ListByFiltersAgreementServiceInterface;

class GetAgreementsService extends AbstractGetService implements GetAgreementsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListByFiltersAgreementServiceInterface $service
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

        $fields = (new GetAgreementResponseDto())->mergeWithMappedFields($fields);

        $serviceDtoData = ($this->service)($filters, $joins, $fields)->read();

        return (new GetAgreementResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }
}
