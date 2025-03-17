<?php

declare(strict_types=1);

namespace Wise\Receiver\Domain\Receiver\Service;

use Wise\Cart\Domain\Cart\Cart;
use Wise\Client\Domain\Client\Client;
use Wise\Core\Domain\AbstractEntityDomainService;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;
use Wise\Core\Helper\QueryFilter\QueryJoinsHelper;
use Wise\Core\Model\QueryJoin;
use Wise\Receiver\Domain\Receiver\Exceptions\ReceiverNotFoundException;
use Wise\Receiver\Domain\Receiver\ReceiverRepositoryInterface;
use Wise\Receiver\Domain\Receiver\Service\Interfaces\ReceiverServiceInterface;

/**
 * Serwis dla domeny Receiver
 */
class ReceiverService extends AbstractEntityDomainService implements ReceiverServiceInterface
{
    public function __construct(
        private readonly ReceiverRepositoryInterface $repository,
        private readonly EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper
    ){
        parent::__construct(
            repository: $repository,
            notFoundException: ReceiverNotFoundException::class,
            entityDomainServiceShareMethodsHelper: $entityDomainServiceShareMethodsHelper
        );
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

        if(array_key_exists('clientId', $fieldsWhichRequireJoin) || array_key_exists('cart', $fieldsWhichRequireJoin)){
            $joins[] = new QueryJoin(Client::class, 'clientId', ['clientId' => 'clientId.id'], QueryJoin::JOIN_TYPE_LEFT);
        }

        if (array_key_exists('cart', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(Cart::class,  'cart', ['clientId.id' => 'cart.clientId'], QueryJoin::JOIN_TYPE_LEFT);
        }

        return $joins;
    }

    /**
     * Metoda do wyciągnięcia imienia i nazwiska z nazwy, która jest w formacie "Imię Nazwisko",
     * jeśli nie ma nazwiska, to zwracamy tylko imię
     */
    public function getFirstAndLastNameFromName(string $name): array
    {
        $data = explode(' ', $name);

        $firstName = $data[0] ?? '';
        $lastName = $data[1] ?? '';

        return [$firstName, $lastName];
    }
}
