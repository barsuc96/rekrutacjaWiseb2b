<?php

namespace Wise\Core\Service\ReplicationRequest;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Helper\Object\ObjectNonModelFieldsHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Repository\Doctrine\ReplicationRequestRepositoryInterface;
use Wise\Core\Repository\RepositoryInterface;
use Wise\Core\Service\AbstractAdditionalFieldsService;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\ReplicationRequest\Interfaces\GetReplicationRequestDetailsServiceInterface;

class GetReplicationRequestDetailsService implements GetReplicationRequestDetailsServiceInterface
{
    /**
     * Pełna nazwa klasy za pomocą ::class
     * Nie wymagane ponieważ nazwe encji pobieramy z repozytorium
     */
    protected const ENTITY_CLASS = null;

    /**
     * Pola obsługiwane ręcznie przez metody
     * Klucz to nazwa pola a wartość to nazwa metody obsługującej
     */
    protected const MANUALLY_HANDLED_FIELDS = [];

    /**
     * Lista agregatów
     */
    protected const AGGREGATES = [];

    public function __construct(
        private readonly ReplicationRequestRepositoryInterface $repository,
        private readonly ?AbstractAdditionalFieldsService $additionalFieldsService = null,
    ){}

    public function __invoke(CommonDetailsParams $params): CommonServiceDTO
    {
        $filters = $this->prepareFiltersBySettings($params);

        // Dodanie dodatkowych filtrów (możliwość przeciążenia)
        $this->prepareFinalFilters($params, $filters);

        $joins = $this->prepareJoins($params, $filters);

        $nonModelFields = ObjectNonModelFieldsHelper::find(
            class: $this->getEntityClass(),
            fields: $this->getFieldsFromParams($params),
            fieldsEnabledToNonModelFields: array_keys(static::MANUALLY_HANDLED_FIELDS)
        );

        $entity = $this->repository->getByIdView(
            filters: $filters,
            fields: $this->prepareFields($nonModelFields, $params),
            joins: $joins,
            aggregates: array_merge(static::AGGREGATES, $params->getAggregates() ?? [])
        );

        if(empty($entity)){
            if($params->getExecuteExceptionWhenEntityNotExists()){
                $this->executeExceptionWhenEntityNotExists($entity, $params);
            }else{
                return new CommonServiceDTO();
            }
        }

        $this->afterFindEntity($entity);

        $this->prepareResult($entity);

        ($resultDTO = new CommonServiceDTO())->writeAssociativeArray($entity);

        return $resultDTO;
    }

    /**
     * Przygotowuje filtry na podstawie ustawień
     * @param CommonDetailsParams $params
     * @return array
     */
    protected function prepareFiltersBySettings(CommonDetailsParams $params): array
    {
        $filters = $params->getFilters();

        if($params->isActive()){
            $filters[] = new QueryFilter('isActive', $params->isActive());
        }

        if(!empty($params->getId())){
            $filters[] = new QueryFilter('id', $params->getId());
        }

        return $filters;
    }

    /**
     * Metoda służy do dodania dodatkowych filtrów
     * @param CommonListParams $params
     * @param array $filters
     */
    protected function prepareFinalFilters(CommonDetailsParams $params, array &$filters): void
    {
        return;
    }

    /**
     * Zwraca listę joinów dołączonych do zapytania
     * @param CommonDetailsParams $params
     * @param QueryFilter[] $filters
     * @return array
     */
    protected function prepareJoins(CommonDetailsParams $params, array $filters): array
    {
        return [];
    }

    /**
     * Przygotowuje dane cache dla obsługi dodatkowych pól przez providery
     * @param array $entity
     * @param array $nonModelFields
     * @param array|null $dateToCache
     * @return array
     */
    protected function prepareCacheData(array $entity, array $nonModelFields, ?array $dateToCache): array
    {
        return $dateToCache ?? [];
    }

    /**
     * Wyjątek, gdy encja nie istnieje
     * @param array $entity
     * @param CommonDetailsParams $params
     * @return void
     */
    protected function executeExceptionWhenEntityNotExists(array $entity, CommonDetailsParams $params): void
    {
        throw new ObjectNotFoundException();
    }

    /**
     * Zwraca klasę encji
     * @return string
     */
    protected function getEntityClass(): string
    {
        if(static::ENTITY_CLASS === null){
            return $this->repository->getEntityClass();
        }

        return static::ENTITY_CLASS;
    }

    /**
     * Umożliwia dodatkowe przygotowanie danych przed zwróceniem ich
     * @param array|null $entities
     * @return void
     */
    protected function prepareResult(?array &$entity): void
    {
        return;
    }

    /**
     * Zwraca listę pól, które ma ostatecznie posiadać rezultat
     * @param CommonDetailsParams $params
     * @return array
     */
    protected function getFieldsFromParams(CommonDetailsParams $params): array
    {
        return $params->getFields();
    }

    /**
     * Przygotowuje listę pól do zwrócenia z SQL
     * @param array $nonModelFields
     * @param CommonDetailsParams $params
     * @return array
     */
    protected function prepareFields(array $nonModelFields, CommonDetailsParams $params): array
    {
        return array_diff($this->getFieldsFromParams($params), $nonModelFields);
    }

    /**
     * Metoda wywoływana po znalezieniu elementu
     * @param array $entity
     * @return void
     */
    protected function afterFindEntity(array $entity): void
    {
        return;
    }
}
