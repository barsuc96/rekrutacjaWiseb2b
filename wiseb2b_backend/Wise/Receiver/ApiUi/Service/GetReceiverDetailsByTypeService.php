<?php

namespace Wise\Receiver\ApiUi\Service;

use Exception;
use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsService;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Order\ApiUi\Dto\Orders\Model\ReceiverResponseDto;
use Wise\Receiver\ApiUi\Dto\ReceiversResponseDto;
use Wise\Receiver\ApiUi\Service\Interfaces\GetReceiverDetailsByTypeServiceInterface;
use Wise\Receiver\Domain\Receiver\Service\Interfaces\ReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiversForCurrentUserServiceInterface;

class GetReceiverDetailsByTypeService extends AbstractGetDetailsService implements GetReceiverDetailsByTypeServiceInterface
{
    public function __construct(
        private readonly UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListReceiversForCurrentUserServiceInterface $getReceiverDetailsService,
        private readonly ReceiverServiceInterface $receiverService,
    ) {
        parent::__construct($shareMethodsHelper);
    }

    /**
     * @throws Exception
     */
    public function get(ParameterBag $parameters): array
    {
        $clientId = $parameters->get('clientId') ? (int)$parameters->get('clientId') : null;
        $type = $parameters->get('receiverType') ? (string)$parameters->get('receiverType') : null;

        $filters = [
            new QueryFilter('clientId', $clientId),
            new QueryFilter('type', $type),
        ];

        /**
         * Pola z wartością null, zostaną usunięte z listy $fields
         */
        $fields = [
            'address.countryCode' => 'deliveryAddress.countryCode',
            'address.street' => 'deliveryAddress.street',
            'address.postalCode' => 'deliveryAddress.postalCode',
            'address.city' => 'deliveryAddress.city',
            'address.building' => 'deliveryAddress.houseNumber',
            'address.apartment' => 'deliveryAddress.apartmentNumber',
            'lp' => null,
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'type' => 'type',
        ];

        $fields = (new ReceiversResponseDto())->mergeWithMappedFields($fields);

        /**
         * Przekazanie parametrów do serwisu
         */
        $params = new CommonListParams();

        $params
            ->setFilters($filters)
            ->setFields($fields)
            ->setFetchTotalCount(true);

        $serviceDto = ($this->getReceiverDetailsService)($params);
        $serviceDtoData = $serviceDto->read();

        if (count($serviceDtoData) > 0) {
            $serviceDtoData = reset($serviceDtoData);
        }

        if(empty($serviceDtoData)){
            return [];
        }

        $this->fillNotExistDeliveryAddress($serviceDtoData);
        $receiverResponseDto = $this->shareMethodsHelper->prepareSingleObjectResponseDto(ReceiverResponseDto::class, $serviceDtoData, $fields);

        /**
         * Obsługujemy dodatkowe pola oraz dodajemy liczbe porządkową
         *
         * @var ReceiverResponseDto $receiverResponseDto
         */
        $queryParameters = QueryParametersHelper::prepareStandardParameters($filters);
        $lp = $queryParameters->getOffset();
        $receiverResponseDto->setLp(++$lp);

        return $receiverResponseDto->resolveArrayData();
    }

    protected function fillNotExistDeliveryAddress(?array &$serviceDtoData): void
    {
        if (empty($serviceDtoData['deliveryAddress'])) {
            $serviceDtoData['deliveryAddress'] = [
                'countryCode' => null,
                'street' => null,
                'postalCode' => null,
                'city' => null,
                'houseNumber' => null,
                'apartmentNumber' => null,
            ];
        }
    }
}
