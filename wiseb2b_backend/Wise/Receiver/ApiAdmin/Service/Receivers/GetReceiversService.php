<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiAdmin\Service\Receivers;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Client\Domain\Client\Client;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\QueryJoin;
use Wise\Receiver\ApiAdmin\Dto\Receivers\GetReceiverResponseDto;
use Wise\Receiver\ApiAdmin\Service\Receivers\Interfaces\GetReceiversServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListByFiltersReceiverServiceInterface;

class GetReceiversService extends AbstractGetService implements GetReceiversServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListByFiltersReceiverServiceInterface $service
    ){
        parent::__construct($adminApiShareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [];
        $joins = [];

        $joins['client1'] = new QueryJoin(Client::class, 'clientId', ['clientId' => 'clientId.id'], QueryJoin::JOIN_TYPE_LEFT);

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'clientId') {
                $field = 'clientId.idExternal';
            }

            if ($field === 'id') {
                $field = 'idExternal';
            }

            $filters[] = new QueryFilter($field, $value);
        }

        $fields =
            [
                'id' => 'idExternal',
                'internalId' => 'id',
                'clientId' => 'clientId.idExternal',
                'address.name' => 'deliveryAddress.name',
                'address.street' => 'deliveryAddress.street',
                'address.houseNumber' => 'deliveryAddress.houseNumber',
                'address.apartmentNumber' => 'deliveryAddress.apartmentNumber',
                'address.postalCode' => 'deliveryAddress.postalCode',
                'address.state' => 'deliveryAddress.state',
                'address.city' => 'deliveryAddress.city',
                'address.countryCode' => 'deliveryAddress.countryCode',
                'firstName' => 'firstName',
                'lastName' => 'lastName',
            ];

        $fields = (new GetReceiverResponseDto())->mergeWithMappedFields($fields);

        $serviceDtoData = ($this->service)($filters, $joins, $fields)->read();

        return (new GetReceiverResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }
}
