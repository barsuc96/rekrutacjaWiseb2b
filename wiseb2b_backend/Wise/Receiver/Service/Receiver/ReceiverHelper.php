<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;

use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\ClientServiceInterface;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractHelper;
use Wise\Core\Service\CommonListParams;
use Wise\I18n\Service\Country\Interfaces\ListCountriesServiceInterface;
use Wise\Receiver\Domain\Receiver\Exceptions\ReceiverNotFoundException;
use Wise\Receiver\Domain\Receiver\Receiver;
use Wise\Receiver\Domain\Receiver\ReceiverRepositoryInterface;
use Wise\Receiver\Domain\Receiver\Service\Interfaces\ReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverHelperInterface;

class ReceiverHelper extends AbstractHelper implements ReceiverHelperInterface
{
    public function __construct(
        private readonly ReceiverServiceInterface $receiverService,
        private readonly ReceiverRepositoryInterface $repository,
        private readonly ClientServiceInterface $clientService,
        private readonly ListCountriesServiceInterface $listCountriesService,
    ){
        parent::__construct($receiverService);
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function getOrCreateClient(array $data): Client
    {
        $clientId = $data['clientId'] ?? null;
        $clientIdExternal = $data['clientIdExternal'] ?? null;

        return $this->clientService->getOrCreateClient($clientId, $clientIdExternal);
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function findReceiverForModify(array $data = []): ?Receiver
    {
        $receiver = null;
        $id = $data['id'] ?? null;
        $idExternal = $data['idExternal'] ?? null;
        $clientId = $data['clientId'] ?? null;
        $type = $data['type'] ?? null;

        //Szukamy po id wewnętrznym
        if ($id) {
            $receiver = $this->repository->findOneBy(['id' => $id]);

            if (!$receiver instanceof Receiver) {
                throw ReceiverNotFoundException::id($id);
            }
        }

        //Jeśli nie znaleźliśmy wewnętrznym, szukamy po zewnętrznym id, jeśli został wysłany
        if ($receiver === null && $idExternal) {
            $receiver = $this->repository->findOneBy(['idExternal' => $idExternal]);
        }

        //Jeśli nie znaleźliśmy wewnętrznym, szukamy po id klienta i konkretnym typie, jeśli został wysłany
        if ($receiver === null && $clientId && $type) {
            $receiver = $this->repository->findOneBy(['clientId' => $clientId, 'type' => $type]);
        }

        return $receiver;
    }

    public function validateCountryCode(?string $countryCode): void
    {
        if($countryCode === null){
            return;
        }

        $paramsListCountries = new CommonListParams();
        $paramsListCountries
            ->setFilters([
                new QueryFilter('isActive', true),
                new QueryFilter('limit', null)
            ])
            ->setFields(['id', 'idExternal']) ;

        $result = ($this->listCountriesService)($paramsListCountries)->read();

        $countryCodes = array_column($result, 'idExternal');

        if (!in_array($countryCode, $countryCodes)) {
            throw new CommonLogicException('Nieprawidłowy kod kraju. Dostępne: '.implode(', ', $countryCodes));
        }
    }

    public function prepareAddressDtoData(array $data): array
    {
        if(isset($data['houseNumber'])){
            $data['building'] = $data['houseNumber'];
            unset($data['houseNumber']);
        }

        if(isset($data['apartmentNumber'])){
            $data['apartment'] = $data['apartmentNumber'];
            unset($data['apartmentNumber']);
        }

        $data['building'] = (isset($data['building']) && !empty($data['building'])) ?  $data['building'] : '';
        $data['street'] = (isset($data['street']) && !empty($data['street'])) ?  $data['street'] : '';
        $data['city'] = (isset($data['city']) && !empty($data['city'])) ?  $data['city'] : '';
        $data['apartment'] = (isset($data['apartment']) && !empty($data['apartment'])) ? $data['apartment'] : '';
        $data['countryCode'] = (isset($data['countryCode']) && !empty($data['countryCode'])) ?  $data['countryCode'] : '';
        $data['postalCode'] = (isset($data['postalCode']) && !empty($data['postalCode'])) ?  $data['postalCode'] : '';
        $data['country'] = (isset($data['country']) && !empty($data['country'])) ?  $data['country'] : '';
        $data['state'] = (isset($data['state']) && !empty($data['state'])) ?  $data['state'] : '';

        return $data;
    }

    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param array $data
     * @param bool $executeNotFoundException
     * @return int|null
     */
    public function getIdIfExistByDataExternal(array $data, bool $executeNotFoundException = true): ?int
    {
        $id = $data['receiverId'] ?? null;
        $idExternal = $data['receiverIdExternal'] ?? $data['receiverExternalId'] ?? null;

        return $this->receiverService->getIdIfExist($id, $idExternal, $executeNotFoundException);
    }

    /**
     * Zwraca identyfikator encji na podstawie date, jeśli znajdują się tam zewnętrzne klucze
     * @param array $data
     * @param bool $executeNotFoundException
     * @return void
     */
    public function prepareExternalData(array &$data, bool $executeNotFoundException = true): void
    {
        // Sprawdzam, czy istnieją pola
        if(!isset($data['receiverId']) && !isset($data['receiverIdExternal']) && !isset($data['receiverExternalId'])){
            return;
        }

        // Pobieram identyfikator
        $id = $data['receiverId'] ?? null;
        $idExternal = $data['receiverIdExternal'] ?? $data['receiverExternalId'] ?? null;

        $data['receiverId'] = $this->receiverService->getIdIfExist($id, $idExternal, $executeNotFoundException);

        // Usuwam pola zewnętrzne
        unset($data['receiverIdExternal']);
        unset($data['receiverExternalId']);
    }
}
