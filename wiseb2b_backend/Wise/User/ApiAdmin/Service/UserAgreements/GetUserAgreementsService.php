<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\UserAgreements;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\QueryJoin;
use Wise\User\ApiAdmin\Dto\UserAgreements\GetUserAgreementResponseDto;
use Wise\User\ApiAdmin\Service\UserAgreements\Interfaces\GetUserAgreementsServiceInterface;
use Wise\User\Domain\Agreement\Agreement;
use Wise\User\Domain\User\User;
use Wise\User\Service\UserAgreement\Interfaces\ListByFiltersUserAgreementServiceInterface;

class GetUserAgreementsService extends AbstractGetService implements GetUserAgreementsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListByFiltersUserAgreementServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [];
        $joins = [];

        $joins['u1'] = new QueryJoin(User::class, 'u1', ['userId' => 'u1.id']);
        $joins['a1'] = new QueryJoin(Agreement::class, 'a1', ['agreementId' => 'a1.id']);

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'id') {
                $field = 't0.idExternal';
            }
            if ($field === 'userId') {
                $field = 'u1.idExternal';
            }
            if ($field === 'agreementId') {
                $field = 'a1.idExternal';
            }

            $filters[] = new QueryFilter($field, $value);
        }

        $fields = [
            'id' => 't0.idExternal',
            'internalId' => 't0.id',
            'userId' => 'u1.idExternal',
            'userInternalId' => 'u1.id',
            'agreementId' => 'a1.idExternal',
            'agreementInternalId' => 'a1.id'
        ];

        $fields = (new GetUserAgreementResponseDto())->mergeWithMappedFields($fields);

        $serviceDtoData = ($this->service)($filters, $joins, $fields)->read();

        return (new GetUserAgreementResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }
}
