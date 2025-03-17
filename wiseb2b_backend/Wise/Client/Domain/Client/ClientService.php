<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client;

use Wise\Client\Domain\Client\Exceptions\ClientNotFoundException;
use Wise\Client\Domain\ClientGroup\ClientGroup;
use Wise\Core\Domain\AbstractEntityDomainService;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Helper\QueryFilter\QueryJoinsHelper;
use Wise\Core\Model\QueryJoin;
use Wise\Delivery\Domain\DeliveryMethod\DeliveryMethod;
use Wise\Payment\Domain\PaymentMethod\PaymentMethod;
use Wise\Pricing\Domain\PriceList\PriceList;
use Wise\Receiver\Domain\Receiver\Receiver;
use Wise\User\Domain\Trader\Trader;
use Wise\User\Domain\User\User;

class ClientService extends AbstractEntityDomainService implements ClientServiceInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper
    ) {
        parent::__construct(
            repository: $clientRepository,
            notFoundException: ClientNotFoundException::class,
            entityDomainServiceShareMethodsHelper: $entityDomainServiceShareMethodsHelper
        );
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function getOrCreateClient($clientId, $clientIdExternal): Client
    {

        $client = null;

        // Sprawdzamy czy istnieje powiązany product po jego id, a jeżeli nie podano to po jego idExternal
        if (!is_null($clientId)) {
            $client = $this->clientRepository->findOneBy(['id' => $clientId]);
        } elseif (!is_null($clientIdExternal)) {
            $client = $this->clientRepository->findOneBy(['idExternal' => $clientIdExternal]);
        }

        // Jeżeli produktu nie ma w bazie to dodajemy jego wpis, ale jako nieaktywny
        if (!($client instanceof Client)) {
            if (!is_null($clientIdExternal)) {
                $client = (new Client())->setIsActive(false)->setIdExternal($clientIdExternal);
                $client = $this->clientRepository->save($client);
            } else {
                throw new ObjectNotFoundException('Obiekt Client nie istnieje i niemożliwy do założenia');
            }
        }

        return $client;
    }

    public function getStreetWithNumber($street, $houseNumber): string
    {
        return ($street ?? '') . ' ' . ($houseNumber ?? '');
    }

    /**
     * Metoda na podstawie wskazanych do wyciągnięcia pól ($fieldNames) przygotowuje joiny do zapytania
     */
    public function prepareJoins(?array $fieldsArray): array
    {
        $fieldsWhichRequireJoin = QueryJoinsHelper::prepareFieldsWhichRequireJoinsByFieldNames($fieldsArray);

        $joins = [];

        if (array_key_exists('userId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(User::class, 'userId', ['id' => 'userId.clientId']);
        }

        if (array_key_exists('clientId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(Client::class, 'clientId', ['clientId' => 'clientId.id'], QueryJoin::JOIN_TYPE_LEFT);
        }

        if (array_key_exists('receiverId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(Receiver::class, 'receiverId', ['receiverId' => 'receiverId.id'], QueryJoin::JOIN_TYPE_LEFT);
        }

        if (array_key_exists('deliveryMethodId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(DeliveryMethod::class, 'deliveryMethodId', ['deliveryMethodId' => 'deliveryMethodId.id']);
        }

        if (array_key_exists('paymentMethodId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(PaymentMethod::class, 'paymentMethodId', ['paymentMethodId' => 'paymentMethodId.id']);
        }

        if (array_key_exists('clientParentId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(Client::class, 'clientParentId', ['clientParentId' => 'clientParentId.id'], QueryJoin::JOIN_TYPE_LEFT);
        }

        if (array_key_exists('defaultPaymentMethodId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(PaymentMethod::class, 'defaultPaymentMethodId', ['defaultPaymentMethodId' => 'defaultPaymentMethodId.id'], QueryJoin::JOIN_TYPE_LEFT);
        }

        if (array_key_exists('defaultDeliveryMethodId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(DeliveryMethod::class, 'defaultDeliveryMethodId', ['defaultDeliveryMethodId' => 'defaultDeliveryMethodId.id'], QueryJoin::JOIN_TYPE_LEFT);
        }

        if (array_key_exists('traderId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(Trader::class, 'traderId', ['traderId' => 'traderId.id'], QueryJoin::JOIN_TYPE_LEFT);
        }

        if (array_key_exists('pricelistId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(PriceList::class, 'pricelistId', ['pricelistId' => 'pricelistId.id'], QueryJoin::JOIN_TYPE_LEFT);
        }

        if (array_key_exists('clientGroupId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(ClientGroup::class, 'clientGroupId', ['clientGroupId' => 'clientGroupId.id'], QueryJoin::JOIN_TYPE_LEFT);
        }

        return $joins;
    }

    /**
     * Zwraca typ adresu określającego główny adres klienta
     * @return string
     */
    public function getRegisterAddressEntityFieldName(): string
    {
        return $this->clientRepository->getRegisterAddressEntityFieldName();
    }

    public function getStoreId()
    {

    }
}
