<?php

namespace Wise\GPSR\Repository\Doctrine;

use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Doctrine\Persistence\ManagerRegistry;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Repository\AbstractRepository;
use Wise\Core\Repository\Doctrine\GlobalAddressRepository;
use Wise\Core\Repository\Doctrine\GlobalAddressRepositoryInterface;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplier;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplierRepositoryInterface;

class GpsrSupplierRepository extends AbstractRepository implements GpsrSupplierRepositoryInterface
{
    protected const ENTITY_CLASS = GpsrSupplier::class;

    protected const ENTITY_FIELD_NAME_ADDRESS = 'address';

    public function __construct(
        ManagerRegistry $registry,
        private readonly GlobalAddressRepositoryInterface $globalAddressRepository
    ){
        parent::__construct($registry);
    }

    public function save(AbstractEntity $entity, bool $flush = false): AbstractEntity
    {
        $this->getEntityManager()->persist($entity);
        $this->persistTranslations($entity);

        // Zapisywanie adresu
        $this->globalAddressRepository->prepareAndSaveGlobalAddress(
            $entity,
            self::ENTITY_FIELD_NAME_ADDRESS
        );

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function findByQueryFiltersView(
        array $queryFilters,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        ?array $fields = [],
        ?array $joins = [],
        ?array $aggregates = [],
    ): array {
        $addressesFields = [];
        $fieldsFlipped = array_flip($fields);

        if (in_array(self::ENTITY_FIELD_NAME_ADDRESS, $fieldsFlipped, true)) {
            $addressesFields[] = self::ENTITY_FIELD_NAME_ADDRESS;
            unset($fields[$fieldsFlipped[self::ENTITY_FIELD_NAME_ADDRESS]]);
        }

        $entities = parent::findByQueryFiltersView(
            queryFilters: $queryFilters,
            orderBy: $orderBy,
            limit: $limit,
            offset: $offset,
            fields: $fields,
            joins: $joins,
            aggregates: $aggregates
        );

        $this->fetchAddresses($entities, $addressesFields);

        return $entities;
    }

    /**
     * Uzupełnianie adresów
     * @param array $entities
     * @param array $addressesFields
     * @throws FeatureNotImplemented
     */
    protected function fetchAddresses(array &$entities, array $addressesFields): void
    {
        // jeśli nie ma tych pól to pomiń
        if (empty($addressesFields)) {
            return;
        }

        $fetchAddress = in_array(self::ENTITY_FIELD_NAME_ADDRESS, $addressesFields, true);

        // Mapowanie dostawców (gdzie kluczem jest id dostawcy)
        $suppliersMapping = ArrayHelper::rearrangeKeysWithValuesUsingReferences($entities);

        $suppliersIdsList = array_keys($suppliersMapping);
        $addresses = [];
        $fieldsToFetch = [...GlobalAddressRepository::ADDRESS_FIELDS_LIST, 'entityId', 'fieldName'];

        // Pobranie adresów dostaawców
        if ($fetchAddress) {
            $addresses = $this->globalAddressRepository->getGlobalAddressesForEntityIds(
                entityName: self::ENTITY_CLASS,
                entityFieldName: self::ENTITY_FIELD_NAME_ADDRESS,
                entityIds: $suppliersIdsList,
                fields: $fieldsToFetch
            );
        }

        // Umieszczenie adresów w odpowiednich encjach
        foreach ($addresses as &$address) {
            $entityId = $address['entityId'];
            $fieldName = $address['fieldName'];
            unset($address['entityId'], $address['fieldName']);
            $suppliersMapping[$entityId][$fieldName] = $address;
        }

        // Uzupełnianie brakujących adresów
        foreach ($entities as &$entity) {
            if ($fetchAddress && empty($suppliersMapping[$entity['id']][self::ENTITY_FIELD_NAME_ADDRESS])) {
                $entity[self::ENTITY_FIELD_NAME_ADDRESS] = [];
            }else{
                $entity[self::ENTITY_FIELD_NAME_ADDRESS] = $suppliersMapping[$entity['id']][self::ENTITY_FIELD_NAME_ADDRESS];
            }
        }
    }


}
