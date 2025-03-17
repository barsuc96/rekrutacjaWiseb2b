<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Service\Clients;

use Wise\Client\ApiAdmin\Dto\Clients\ClientDeliveryMethodAggregateDto;
use Wise\Client\ApiAdmin\Dto\Clients\ClientPaymentMethodAggregateDto;
use Wise\Client\ApiAdmin\Dto\Clients\PutClientDto;
use Wise\Client\ApiAdmin\Service\Clients\Interfaces\PutClientsServiceInterface;
use Wise\Client\Service\Client\Interfaces\AddOrModifyClientServiceInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\AddOrModifyClientDeliveryMethodServiceInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\ListByFiltersClientDeliveryMethodServiceInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\RemoveClientDeliveryMethodServiceInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\AddOrModifyClientPaymentMethodServiceInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\ListByFiltersClientPaymentMethodServiceInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\RemoveClientPaymentMethodServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutAdminApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonRemoveParams;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverHelperInterface;

class PutClientsService extends AbstractPutAdminApiService implements PutClientsServiceInterface
{
    public function __construct(
        protected readonly AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyClientServiceInterface $addOrModifyClientService,
        private readonly AddOrModifyClientPaymentMethodServiceInterface $clientPaymentMethodService,
        private readonly AddOrModifyClientDeliveryMethodServiceInterface $clientDeliveryMethodService,
        private readonly RemoveClientDeliveryMethodServiceInterface $removeClientDeliveryMethodService,
        private readonly RemoveClientPaymentMethodServiceInterface $removeClientPaymentMethodService,
        private readonly ListByFiltersClientPaymentMethodServiceInterface $listByFiltersClientPaymentMethodService,
        private readonly ListByFiltersClientDeliveryMethodServiceInterface $listByFiltersClientDeliveryMethodService,
        private readonly ReceiverHelperInterface $receiverHelper,
    ) {
        parent::__construct($adminApiShareMethodsHelper, $addOrModifyClientService);
    }

    /**
     * Metoda pomocnicza, która pozwala wykonać pewne czynności przed przetworzeniem/wykonaniem serwisu
     * @param AbstractDto $putDto
     * @param bool $isPatch
     * @return void
     * @throws \ReflectionException
     */
    public function prepareData(AbstractDto $putDto, bool $isPatch): void
    {
        if($putDto->isInitialized('registerAddress') && $putDto->getRegisterAddress() !== null){
            $this->receiverHelper->validateCountryCode($putDto?->getRegisterAddress()?->getCountryCode());
        }
    }

    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param AbstractDto $dto
     * @param bool $isPatch
     * @return CommonModifyParams|CommonServiceDTO
     */
    protected function fillParams(AbstractDto $dto, bool $isPatch): CommonModifyParams|CommonServiceDTO
    {
        $serviceDTO = parent::fillParams($dto, $isPatch);
        $data = $serviceDTO->read();

        // Usunięcie pól payments i deliveries, ponieważ są obsługiwane w innym miejscu
        unset($data['payments'], $data['deliveries']);
        $serviceDTO->writeAssociativeArray($data);

        return $serviceDTO;
    }

    /**
     * Pozwala wykonać pewne czynności po wykonaniu serwisu
     * @param array $serviceDtoData
     * @param AbstractDto $dto
     * @param bool $isPatch
     * @return void
     */
    public function afterExecuteService(array &$serviceDtoData, AbstractDto $dto, bool $isPatch): void
    {
        parent::afterExecuteService($serviceDtoData, $dto, $isPatch);

        $this->prepareAndPutClientPaymentMethod($dto, $isPatch);
        $this->prepareAndPutClientDeliveryMethod($dto, $isPatch);
    }

    /**
     * Przygotowuje i zapisuje dane dotyczące metod płatności klienta
     * @param PutClientDto|AbstractDto $putClientDto
     * @param bool $isPatch
     * @return void
     * @throws \ReflectionException
     */
    protected function prepareAndPutClientPaymentMethod(PutClientDto|AbstractDto $putClientDto, bool $isPatch = false): void
    {
        if (false === $putClientDto->isInitialized('payments')) {
            return;
        }

        $affectedPaymentMethodIds = $this->addOrModifyClientPaymentMethods($putClientDto, $isPatch);

        if (!$isPatch) {
            $clientInternalId = $putClientDto->getInternalId();
            $paymentMethodIdsToRemove = $this->getClientPaymentMethodIdsToRemove(
                $clientInternalId,
                $affectedPaymentMethodIds
            );

            if (!empty($paymentMethodIdsToRemove)) {
                $this->removeUnmodifiedClientPaymentMethods($paymentMethodIdsToRemove, $clientInternalId);
            }
        }
    }

    /**
     * Przygotowuje i zapisuje dane dotyczące metod dostawy klienta
     * @param PutClientDto|AbstractDto $putClientDto
     * @param bool $isPatch
     * @return void
     * @throws \ReflectionException
     */
    protected function prepareAndPutClientDeliveryMethod(PutClientDto|AbstractDto $putClientDto, bool $isPatch = false): void
    {
        if (false === $putClientDto->isInitialized('deliveries')) {
            return;
        }

        $affectedPaymentMethodIds = $this->addOrModifyClientDeliveryMethod($putClientDto, $isPatch);

        if (!$isPatch) {
            $clientInternalId = $putClientDto->getInternalId();
            $deliveryMethodIdsToRemove = $this->getClientDeliveryMethodIdsToRemove(
                $clientInternalId,
                $affectedPaymentMethodIds
            );

            if (!empty($deliveryMethodIdsToRemove)) {
                $this->removeUnmodifiedClientDeliveryMethods($deliveryMethodIdsToRemove, $clientInternalId);
            }
        }
    }

    /**
     * Dodaje lub modyfikuje metody płatności klienta
     * @param PutClientDto|AbstractDto $putClientDto
     * @param bool $isPatch
     * @return array
     */
    protected function addOrModifyClientPaymentMethods(PutClientDto|AbstractDto $putClientDto, bool $isPatch): array
    {
        $clientPaymentMethodFieldMap = [
            'internalId' => 'id',
            'clientId' => 'clientExternalId',
            'clientInternalId' => 'clientId',
            'paymentMethodId' => 'paymentMethodExternalId',
            'paymentMethodInternalId' => 'paymentMethodId',
            'isActive' => 'isActive',
        ];

        $affectedPaymentMethodIds = [];

        /** @var ClientPaymentMethodAggregateDto $putClientPaymentMethodDto */
        foreach ($putClientDto->getPayments() as $putClientPaymentMethodDto) {
            ($serviceDTO = new CommonModifyParams())->write($putClientPaymentMethodDto, $clientPaymentMethodFieldMap);
            $serviceDTO->mergeWithAssociativeArray([
                'clientExternalId' => $putClientDto->getId(),
                'clientId' => $putClientDto->getInternalId(),
            ]);

            $serviceDTO->setMergeNestedObjects($isPatch);
            $clientPayment = ($this->clientPaymentMethodService)($serviceDTO);
            $this->adminApiShareMethodsHelper->repositoryManager->flush();

            $affectedPaymentMethodIds[] = (int) $clientPayment->read()['paymentMethodId'];
        }

        return $affectedPaymentMethodIds;
    }

    /**
     * Dodaje lub modyfikuje metody dostawy klienta
     * @param PutClientDto|AbstractDto $putClientDto
     * @param bool $isPatch
     * @return array
     */
    protected function addOrModifyClientDeliveryMethod(PutClientDto|AbstractDto $putClientDto, bool $isPatch): array
    {
        $clientDeliveryMethodFieldMap = [
            'internalId' => 'id',
            'clientId' => 'clientExternalId',
            'clientInternalId' => 'clientId',
            'deliveryMethodId' => 'deliveryMethodExternalId',
            'deliveryMethodInternalId' => 'deliveryMethodId',
            'isActive' => 'isActive',
        ];

        $affectedDeliveryMethodIds = [];

        /** @var ClientDeliveryMethodAggregateDto $putClientDeliveryMethodDto */
        foreach ($putClientDto->getDeliveries() as $putClientDeliveryMethodDto) {
            ($serviceDTO = new CommonModifyParams())->write($putClientDeliveryMethodDto, $clientDeliveryMethodFieldMap);

            $serviceDTO->mergeWithAssociativeArray([
                'clientExternalId' => $putClientDto->getId(),
                'clientId' => $putClientDto->getInternalId(),
            ]);

            $serviceDTO->setMergeNestedObjects($isPatch);
            $clientDelivery = ($this->clientDeliveryMethodService)($serviceDTO);
            $this->adminApiShareMethodsHelper->repositoryManager->flush();

            $affectedDeliveryMethodIds[] = (int) $clientDelivery->read()['deliveryMethodId'];
        }

        return $affectedDeliveryMethodIds;
    }

    /**
     * Usuwa niezmodyfikowane metody płatności klienta
     * @param array $paymentMethodIdsToRemove
     * @param int $clientInternalId
     * @return void
     */
    protected function removeUnmodifiedClientPaymentMethods(array $paymentMethodIdsToRemove, int $clientInternalId): void
    {
        $serviceDto = (new CommonRemoveParams());
        foreach ($paymentMethodIdsToRemove as $paymentMethodId) {
            $serviceDto->writeAssociativeArray(
                [
                    'clientId' => $clientInternalId,
                    'paymentMethodId' => $paymentMethodId
                ]
            );

            $serviceDto->setFilters([
                new QueryFilter('clientId', $clientInternalId),
                new QueryFilter('paymentMethodId', $paymentMethodId)
            ]);

            ($this->removeClientPaymentMethodService)($serviceDto);
        }
        $this->adminApiShareMethodsHelper->repositoryManager->flush();
    }

    /**
     * Usuwa niezmodyfikowane metody dostawy klienta
     * @param array $deliveryMethodIdsToRemove
     * @param int $clientInternalId
     * @return void
     */
    protected function removeUnmodifiedClientDeliveryMethods(
        array $deliveryMethodIdsToRemove,
        int $clientInternalId
    ): void {
        $serviceDto = (new CommonRemoveParams());

        foreach ($deliveryMethodIdsToRemove as $deliveryMethodId) {
            $serviceDto->writeAssociativeArray(
                [
                    'clientId' => $clientInternalId,
                    'deliveryMethodId' => $deliveryMethodId
                ]
            );

            $serviceDto->setFilters([
                new QueryFilter('clientId', $clientInternalId),
                new QueryFilter('deliveryMethodId', $deliveryMethodId)
            ]);

            ($this->removeClientDeliveryMethodService)($serviceDto);
        }

        $this->adminApiShareMethodsHelper->repositoryManager->flush();
    }

    /**
     * Pobiera wszystkie metody płatności klienta
     * @param int $clientInternalId
     * @return array
     */
    protected function getAllClientPaymentMethods(int $clientInternalId): array
    {
        $clientPaymentMethodsDto = ($this->listByFiltersClientPaymentMethodService)(
            filters: [
                new QueryFilter('clientId', $clientInternalId)
            ],
            joins: [],
            fields: ['paymentMethodId']
        );

        return $clientPaymentMethodsDto->read();
    }

    /**
     * Pobiera metody płatności klienta do usunięcia
     * @param int $clientInternalId
     * @param array $modifiedPaymentMethodIds
     * @return array
     */
    protected function getClientPaymentMethodIdsToRemove(int $clientInternalId, array $modifiedPaymentMethodIds): array
    {
        $clientPaymentMethodsData = $this->getAllClientPaymentMethods($clientInternalId);
        $clientPaymentMethodsData = array_column($clientPaymentMethodsData, 'paymentMethodId');

        return array_diff($clientPaymentMethodsData, $modifiedPaymentMethodIds);
    }

    /**
     * Pobiera wszystkie metody dostawy klienta
     * @param int $clientInternalId
     * @return array|null
     */
    protected function getAllClientDeliveryMethods(int $clientInternalId): ?array
    {
        $clientDeliveryMethodsDto =  ($this->listByFiltersClientDeliveryMethodService)(
            filters: [
                new QueryFilter('clientId', $clientInternalId)
            ],
            joins: [],
            fields: ['deliveryMethodId']
        );

        return $clientDeliveryMethodsDto->read();
    }

    /**
     * Pobiera metody dostawy klienta do usunięcia
     * @param int $clientInternalId
     * @param $modifiedDeliveryMethodIds
     * @return array
     */
    protected function getClientDeliveryMethodIdsToRemove(int $clientInternalId, $modifiedDeliveryMethodIds): array
    {
        $clientDeliveryMethodsData = $this->getAllClientDeliveryMethods($clientInternalId);
        $clientDeliveryMethodsData = array_column($clientDeliveryMethodsData, 'deliveryMethodId');

        return array_diff($clientDeliveryMethodsData, $modifiedDeliveryMethodIds);
    }
}
