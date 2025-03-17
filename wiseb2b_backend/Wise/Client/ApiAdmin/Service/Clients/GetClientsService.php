<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Service\Clients;

use Wise\Client\ApiAdmin\Service\Clients\Interfaces\GetClientsServiceInterface;
use Wise\Client\Domain\ClientStatus\Enum\ClientStatusEnum;
use Wise\Client\Repository\Doctrine\ClientRepository;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetListAdminApiService;
use Wise\Core\Model\QueryFilter;

class GetClientsService extends AbstractGetListAdminApiService implements GetClientsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ClientRepository $clientRepository,
        private readonly ListClientsServiceInterface $listClientsService
    )
    {
        parent::__construct($adminApiShareMethodsHelper, $listClientsService);
    }

    /**
     * Metoda pozwala na dodanie własnych filtrów do listy filtrów
     * Zwraca wartość bool wskazującą, czy dalsze przetwarzanie bieżącego pola powinno być kontynuowane.
     * Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     * @param array $filters Referencja do tablicy filtrów, do której można dodać własne filtry.
     * @param int|string $field Nazwa parametru do przetworzenia.
     * @param mixed $value Wartość parametru do przetworzenia.
     * @return bool Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     * @example Wise\Order\ApiUi\Service\Orders\GetOrdersService
     */
    protected function customInterpreterParameters(array &$filters, int|string $field, mixed $value): bool
    {
        if ($field === 'active') {
            return true;
        }

        if ($field === 'id') {
            $filters[] = new QueryFilter('idExternal', $value);
            return true;
        }

        if ($field === 'storeId') {
            $filters[] = new QueryFilter('clientGroupId.storeId', intval($value));
            return true;
        }

        if ($field === 'emptyExternalId') {
            if ($value === true || $value === 'true') {
                $filters[] = new QueryFilter('idExternal', null, QueryFilter::COMPARATOR_IS_NULL);
            }
            return true;
        }


        return false;
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        return parent::prepareCustomFieldMapping(array_merge($fieldMapping, [
            'registerAddress' => 'registerAddress',
            'returnBankAccount' => 'returnBankAccount',
        ]));
    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        if (in_array('payments', $this->aggregates)) {
            $this->fields['payments'] = 'payments';
        }
        if (in_array('deliveries', $this->aggregates)) {
            $this->fields['deliveries'] = 'deliveries';
        }

        parent::prepareServiceDtoBeforeTransform($serviceDtoData);
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        if (empty($elementData['returnBankAccount'])) {
            $elementData['returnBankAccount'] = null;
        } else {
            $elementData['returnBankAccount'] = [
                'ownerName' => $elementData['returnBankAccount']['owner_name'],
                'account' => $elementData['returnBankAccount']['account'],
                'bankCountryId' => $elementData['returnBankAccount']['bank_country_id'],
                'bankAddress' => $elementData['returnBankAccount']['bank_address'],
                'bankName' => $elementData['returnBankAccount']['bank_name'],
            ];
        }

        if (empty($elementData['registerAddress'])) {
            $elementData['registerAddress'] = null;
        }

        if (empty($elementData['payments'])) {
            $elementData['payments'] = null;
        } else {
            $payments = [];
            foreach ($elementData['payments'] as $payment) {
                $payments[] = [
                    'internalId' => $payment['id'],
                    'clientId' => $elementData['idExternal'],
                    'clientInternalId' => $elementData['id'],
                    'paymentMethodId' => $payment['paymentMethodIdExternal'],
                    'paymentMethodInternalId' => $payment['paymentMethodId'],
                ];
            }
            $elementData['payments'] = $payments;
        }

        if (empty($elementData['deliveries'])) {
            $elementData['deliveries'] = null;
        } else {
            $deliveries = [];
            foreach ($elementData['deliveries'] as $delivery) {
                $deliveries[] = [
                    'internalId' => $delivery['id'],
                    'clientId' => $elementData['idExternal'],
                    'clientInternalId' => $elementData['id'],
                    'deliveryMethodId' => $delivery['deliveryMethodIdExternal'],
                    'deliveryMethodInternalId' => $delivery['deliveryMethodId'],
                ];
            }
            $elementData['deliveries'] = $deliveries;
        }

        if (empty($elementData['clientRepresentative'])) {
            $elementData['clientRepresentative'] = null;
        }

        if (!empty($elementData['status']) && !empty($elementData['status']['id'])) {
            $elementData['status'] = ClientStatusEnum::from($elementData['status']['id'])->name;
        } else {
            $elementData['status'] = null;
        }
    }
}
