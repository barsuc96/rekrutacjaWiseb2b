<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Domain\Client\ClientServiceInterface;
use Wise\Client\Domain\Client\Exceptions\ClientNotFoundException;
use Wise\Client\Domain\ClientStatus\ClientStatusServiceInterface;
use Wise\Client\Domain\ClientStatus\Enum\ClientStatusEnum;
use Wise\Client\Service\Client\Interfaces\ClientHelperInterface;
use Wise\Client\Service\Client\Interfaces\ListByFiltersClientServiceInterface;
use Wise\Core\DataTransformer\CommonDomainDataTransformer;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Exception\ObjectValidationException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractHelper;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;

class ClientHelper extends AbstractHelper implements ClientHelperInterface
{

    public function __construct(
        private readonly ClientServiceInterface $clientService,
        private readonly ClientRepositoryInterface $repository,
        private readonly ListByFiltersClientServiceInterface $listByFiltersClientService,
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly ClientStatusServiceInterface $clientStatusService
    ) {
        parent::__construct(
            entityDomainService: $clientService
        );
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function getOrCreateParentClient(array $data): ?Client
    {
        $clientId = $data['clientParentId'] ?? null;
        $clientIdExternal = $data['clientParentIdExternal'] ?? null;

        return $clientId !== null || $clientIdExternal !== null ? $this->clientService->getOrCreateClient(
            $clientId,
            $clientIdExternal
        ) : null;
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function findClientForModify(array $data = []): ?Client
    {
        $client = null;
        $id = $data['id'] ?? null;
        $idExternal = $data['idExternal'] ?? null;

        //Szukamy po id wewnętrznym
        if ($id) {
            $client = $this->repository->findOneBy(['id' => $id]);

            if (!$client instanceof Client) {
                throw ClientNotFoundException::id($id);
            }
        }

        //Jeśli nie znaleźliśmy wewnętrznym, szukamy po zewnętrznym id, jeśli został wysłany
        if ($client === null && $idExternal) {
            $client = $this->repository->findOneBy(['idExternal' => $idExternal]);
        }

        return $client;
    }

    public function getClientIdIfExists(?int $id = null, ?string $idExternal = null): ?int
    {
        $clientId = false;

        if (null !== $id) {
            $clientId = current(
                $this->repository->findByQueryFiltersView(
                    queryFilters: [(new QueryFilter('id', $id))],
                    fields: ['id']
                )
            );
        } elseif (null !== $idExternal) {
            $clientId = current(
                $this->repository->findByQueryFiltersView(
                    queryFilters: [(new QueryFilter('idExternal', $idExternal))],
                    fields: ['id']
                )
            );
        }

        if ($clientId === false || !isset($clientId['id'])) {
            throw ClientNotFoundException::id($id);
        }

        return $clientId['id'];
    }

    /**
     * Metoda na podstawie przekazanych danych (zgodnych co do nazw pól encji client) oraz id klienta
     * wyszukuje klienta, weryfikując czy kluczowe dane (id oraz clientData) są ze sobą zgodne
     * @param int|null $id
     * @param array|null $clientData
     * @return int|null
     * @throws ObjectNotFoundException
     */
    public function getClientIdIfExistsByData(?int $id = null, ?array $clientData = null): ?int
    {
        $client = null;

        // Pobranie na podstawie przekazanego id
        if($id !== null){
            $client = ($this->listByFiltersClientService)(
                filters: [
                    new QueryFilter('id', $id)
                ],
                joins: [],
                fields: []
            )->read();
        }

        // Jeśli nie znaleziono na podstawie id, zrób to na podstawie clientData
        if(empty($client) && !empty($clientData)){
            $filters = [];

            // Przygotowanie filtrów na podstawie danych
            foreach ($clientData as $field => $value){
                $filters[] = new QueryFilter($field, $value);
            }

            $client = ($this->listByFiltersClientService)(
                filters: $filters,
                joins: [],
                fields: []
            )->read();
        }

        if(empty($client)){
            throw new ObjectNotFoundException('Obiekt Client nie istnieje');
        }

        if(count($client) > 1){
            throw new InvalidInputArgumentException('Znaleziono więcej niż jeden obiekt Client');
        }

        $client = reset($client);

        // Weryfikacja czy dane pasują do siebie
        if(!empty($clientData)){
            foreach ($clientData as $field => $value){
                if(isset($client[$field]) && $client[$field] !== $value){
                    throw new ObjectValidationException('Znaleziono obiekt Client lecz dane przekazane w clientData nie zą zbierzne z pobranym obiektem. Pole: ' . $field . '(' . $client[$field]  . ' => ' . $value . ' )');
                }
            }
        }

        return $client['id'];
    }

    /**
     * Zwraca walute klienta
     * @return string
     */
    public function getCurrencyForCurrentClient(): string
    {
        $clientId = $this->currentUserService->getClientId();

        /** @var Client $client */
        $client = $this->repository->find($clientId);

        return $client?->getDefaultCurrency() ?? $_ENV['DEFAULT_CURRENCY'];
    }

    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param array $data
     * @param bool $executeNotFoundException
     * @return int|null
     * @throws \ReflectionException
     */
    public function getIdIfExistByDataExternal(array $data, bool $executeNotFoundException = true): ?int
    {
        $id = $data['clientGroupId'] ?? null;
        $idExternal = $data['clientGroupIdExternal'] ?? $data['clientGroupExternalId'] ?? null;

        return $this->clientService->getIdIfExist($id, $idExternal, $executeNotFoundException);
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
        if(!isset($data['clientGroupId']) && !isset($data['clientGroupIdExternal']) && !isset($data['clientGroupExternalId'])){
            return;
        }

        // Pobieram identyfikator
        $id = $data['clientGroupId'] ?? null;
        $idExternal = $data['clientGroupIdExternal'] ?? $data['clientGroupExternalId'] ?? null;

        $data['clientGroupId'] = $this->clientService->getIdIfExist($id, $idExternal, $executeNotFoundException);

        // Usuwam pola zewnętrzne
        unset($data['clientGroupIdExternal']);
        unset($data['clientGroupExternalId']);
    }

    /**
     * Zwraca identyfikator encji na podstawie date, jeśli znajdują się tam zewnętrzne klucze
     * @param array $data
     * @param bool $executeNotFoundException
     * @return void
     */
    public function prepareExternalParentClientData(array &$data, bool $executeNotFoundException = true): void
    {
        // Sprawdzam, czy istnieją pola
        if(!isset($data['clientParentId']) && !isset($data['clientParentIdExternal']) && !isset($data['clientParentExternalId'])){
            unset($data['clientParentIdExternal']);
            unset($data['clientParentExternalId']);
            return;
        }

        // Pobieram identyfikator
        $id = $data['clientParentId'] ?? null;
        $idExternal = $data['clientGroupIdExternal'] ?? $data['clientParentExternalId'] ?? null;

        $data['clientParentId'] = $this->clientService->getIdIfExist($id, $idExternal, $executeNotFoundException);

        // Usuwam pola zewnętrzne
        unset($data['clientParentIdExternal']);
        unset($data['clientParentExternalId']);
    }

    /**
     * Zwraca id statusu, jeśli istnieje na podstawie tablicy danych
     * @param array $data
     * @return int|null
     * @throws InvalidInputArgumentException
     */
    public function getClientStatusIdIfExistsByData(array &$data): ?int
    {
        $statusData = CommonDomainDataTransformer::getDataForField($data, 'status');

        $statusDataId = $this->clientStatusService->getStatusIdIfExistsByData($data['status'] ?? null, $statusData);

        CommonDomainDataTransformer::removeDataForField($data,'status');

        return $statusDataId;
    }

}
