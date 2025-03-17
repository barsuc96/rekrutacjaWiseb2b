<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\Users;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Client\Domain\Client\Client;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\QueryJoin;
use Wise\User\ApiAdmin\Dto\Users\GetUserResponseDto;
use Wise\User\ApiAdmin\Service\Users\Interfaces\GetUsersServiceInterface;
use Wise\User\Service\User\Interfaces\ListByFiltersUserServiceInterface;

class GetUsersService extends AbstractGetService implements GetUsersServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListByFiltersUserServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [];
        $joins = [];

        $joins['clientId'] = new QueryJoin(Client::class, 'clientId', ['clientId' => 'clientId.id']);
//        ToDo do odkomentowania gdy będą Role i Trader
//        $joins['roleId'] = new QueryJoin(UserRoles::class, 'roleId', ['roleId' => 'roleId.id']);
//        $joins['traderId'] = new QueryJoin(Traders::class, 'traderId', ['traderId' => 'traderId.id']);

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'id') {
                $field = 'idExternal';
            }
            if ($field === 'internalId') {
                $field = 'id';
            }
            if ($field === 'clientId') {
                $field = 'clientId.idExternal';
            }
            if ($field === 'clientInternalId') {
                $field = 'clientId.id';
            }
//            if ($field === 'roleId') {
//                $field = 'roleId.idExternal';
//            }
            if ($field === 'roleInternalId') {
                $field = 'roleId';
            }
//            if ($field === 'traderId') {
//                $field = 'traderId.idExternal';
//            }
            if ($field === 'traderInternalId') {
                $field = 'traderId';
            }

            $filters[] = new QueryFilter($field, $value);
        }

        $fields = [
            'id' => 'idExternal',
            'internalId' => 'id',
            'clientId' => 'clientId.idExternal',
            'clientInternalId' => 'clientId',
//            'roleId' => 'roleId.idExternal',
            'roleInternalId' => 'roleId',
//            'traderId' => 'traderId.idExternal',
            'traderInternalId' => 'traderId',
        ];

        $fields = (new GetUserResponseDto())->mergeWithMappedFields($fields);

        $serviceDtoData = ($this->service)($filters, $joins, $fields)->read();

        return (new GetUserResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }
}
