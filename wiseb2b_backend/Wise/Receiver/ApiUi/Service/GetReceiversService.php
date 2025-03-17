<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Order\ApiUi\Dto\Orders\Model\ReceiverResponseDto;
use Wise\Receiver\ApiUi\Dto\ReceiversResponseDto;
use Wise\Receiver\ApiUi\Service\Interfaces\GetReceiversServiceInterface;
use Wise\Receiver\Domain\Receiver\Service\Interfaces\ReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiversForCurrentUserServiceInterface;

/**
 * Serwis api - pobierający listę odbiorców w zależności od klienta
 */
class GetReceiversService extends AbstractGetService implements GetReceiversServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListReceiversForCurrentUserServiceInterface $service,
        private readonly ReceiverServiceInterface $receiverService,
    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [];
        $searchKeyword = null;

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'contentLanguage') {
                continue;
            }

            /**
             * Wykluczamy z listy fitrów pole searchKeyword, które przekazujemy osobno przez klasę Params
             */
            if ($field === 'searchKeyword') {
                $searchKeyword = $value;
                continue;
            }

            $filters[] = new QueryFilter($field, $value);
        }

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
            ->setSearchKeyword($searchKeyword)
            ->setFetchTotalCount(true)
            ->onlyActiveRecords();

        $serviceDto = ($this->service)($params);
        $serviceDtoData = $serviceDto->read();
        $this->setTotalCount($serviceDto->getTotalCount());

        $this->fillNotExistDeliveryAddress($serviceDtoData);
        $resultArrayData = $this->shareMethodsHelper->prepareMultipleObjectsResponseDto(ReceiverResponseDto::class, $serviceDtoData, $fields);

        /**
         * Obsługujemy dodatkowe pola oraz dodajemy liczbe porządkową
         *
         * @var ReceiverResponseDto $receiverResponseDto
         */
        $queryParameters = QueryParametersHelper::prepareStandardParameters($filters);
        $lp = $queryParameters->getOffset();
        foreach ($resultArrayData as $key => $receiverResponseDto)
        {
            $receiverResponseDto->setLp(++$lp);
        }

        return $resultArrayData;
    }

    protected function fillNotExistDeliveryAddress(?array &$serviceDtoData): void
    {
        foreach ($serviceDtoData as &$receiver) {
            if (empty($receiver['deliveryAddress'])) {
                $receiver['deliveryAddress'] = [
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
}
