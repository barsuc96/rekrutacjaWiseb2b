<?php

namespace Wise\Receiver\ApiUi\Service;

use Exception;
use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsService;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Order\ApiUi\Dto\Orders\Model\ReceiverResponseDto;
use Wise\Receiver\ApiUi\Service\Interfaces\GetReceiverDetailsServiceInterface;
use Wise\Receiver\Domain\Receiver\Service\Interfaces\ReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\GetReceiverDetailsParams;

class GetReceiverDetailsService extends AbstractGetDetailsService implements GetReceiverDetailsServiceInterface
{
    public function __construct(
        private readonly UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly \Wise\Receiver\Service\Receiver\Interfaces\GetReceiverDetailsServiceInterface $getReceiverDetailsService,
        private readonly ReceiverServiceInterface $receiverService,
    ) {
        parent::__construct($shareMethodsHelper);
    }

    /**
     * @throws Exception
     */
    public function get(ParameterBag $parameters): array
    {
        $receiverId = $parameters->get('receiverId') ? (int)$parameters->get('receiverId') : null;

        /**
         * Mapowanie pól. Pola z '.' to pola z obiektów innych domen
         */
        $fields = [
            'id' => 'id',
            'lp' => 'lp',
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'type' => 'type',
            'address.countryCode' => 'deliveryAddress.countryCode',
            'address.street' => 'deliveryAddress.street',
            'address.postalCode' => 'deliveryAddress.postalCode',
            'address.city' => 'deliveryAddress.city',
            'address.building' => 'deliveryAddress.houseNumber',
            'address.apartment' => 'deliveryAddress.apartmentNumber',
        ];

        $fields = (new ReceiverResponseDto())->mergeWithMappedFields($fields);

        $params = new GetReceiverDetailsParams();

        $params
            ->setReceiverId($receiverId)
            ->setFields($fields);

        $serviceDtoData = ($this->getReceiverDetailsService)($params)->read();

        if(empty($serviceDtoData)){
            throw new ObjectNotFoundException('Nie znaleziono odbiorce o podanym id');
        }

        $this->fillNotExistDeliveryAddress($serviceDtoData);
        $serviceDtoData['lp'] = 1;
        $resultData = $this->shareMethodsHelper->prepareSingleObjectResponseDto(ReceiverResponseDto::class, $serviceDtoData, $fields);

        return $resultData->resolveArrayData();
    }

    protected function fillNotExistDeliveryAddress(?array &$serviceDtoData): void
    {
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
