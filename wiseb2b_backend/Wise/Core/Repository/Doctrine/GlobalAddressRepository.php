<?php

declare(strict_types=1);

namespace Wise\Core\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use PDO;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Exception\QueryFilterComparatorNotSupportedException;
use Wise\Core\Model\Address;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Repository\AbstractRepository;

/**
 * @extends ServiceEntityRepository<GlobalAddress>
 *
 * @method GlobalAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method GlobalAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method GlobalAddress[]    findAll()
 * @method GlobalAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlobalAddressRepository extends AbstractRepository implements GlobalAddressRepositoryInterface
{
    protected const ENTITY_CLASS = GlobalAddress::class;

    public const ADDRESS_FIELDS_LIST = [
        'name',
        'street',
        'houseNumber',
        'apartmentNumber',
        'city',
        'postalCode',
        'countryCode',
        'state',
    ];

    public function removeByEntityName(string $entityName, string $filedName, int $entityId, bool $flush = false): void
    {
        $dql = 'DELETE FROM ' . self::ENTITY_CLASS . ' ga WHERE ';
        $dql .= 'ga.entityName = :entityName AND ';
        $dql .= 'ga.fieldName = :filedName AND ';
        $dql .= 'ga.entityId = :entityId';

        $query = $this->getEntityManager()->createQuery($dql);

        $query->setParameter('entityName', $this->getMappedEntityName($entityName), PDO::PARAM_INT);
        $query->setParameter('fieldName', $filedName, PDO::PARAM_INT);
        $query->setParameter('entityId', $entityId, PDO::PARAM_INT);

        $query->execute();

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws QueryFilterComparatorNotSupportedException
     * @throws FeatureNotImplemented
     */
    public function getGlobalAddress(
        string $entityName,
        string $entityFieldName,
        int $entityId
    ): array {
        $globalAddresses = $this->findByQueryFiltersView(
            [
                new QueryFilter('entityName', $this->getMappedEntityName($entityName)),
                new QueryFilter('fieldName', $entityFieldName),
                new QueryFilter('entityId', $entityId),
            ]
        );
        $globalAddress = array_pop($globalAddresses);

        if ($globalAddress === null) {
            return [];
        }

        return [
            'name' => $globalAddress['name'] ?? null,
            'street' => $globalAddress['street'] ?? null,
            'houseNumber' => $globalAddress['houseNumber'] ?? null,
            'apartmentNumber' => $globalAddress['apartmentNumber'] ?? null,
            'city' => $globalAddress['city'] ?? null,
            'postalCode' => $globalAddress['postalCode'] ?? null,
            'countryCode' => $globalAddress['countryCode'] ?? null,
            'state' => $globalAddress['state'] ?? null
        ];
    }

    /**
     * @throws QueryFilterComparatorNotSupportedException
     * @throws FeatureNotImplemented
     */
    public function getGlobalAddressesForEntityIds(
        string $entityName,
        string $entityFieldName,
        array $entityIds,
        array $fields = self::ADDRESS_FIELDS_LIST
    ): array {
        return $this->findByQueryFiltersView(
            queryFilters: [
                new QueryFilter('entityName', $this->getMappedEntityName($entityName)),
                new QueryFilter('fieldName', $entityFieldName),
                new QueryFilter('entityId', $entityIds, QueryFilter::COMPARATOR_IN),
            ],
            fields: $fields
        );
    }

    /**
     * Przygotowanie i zapis adresu
     * @param AbstractEntity $entity
     * @param $entityFieldName
     * @param $isActive
     * @return void
     * @throws \ReflectionException
     */
    public function prepareAndSaveGlobalAddress(
        AbstractEntity $entity,
        $entityFieldName,
        $isActive = true
    ): void {
        $address = null;

        //Tworzymy nazwę metody którą chcemy odpalić
        $getMethod = 'get' . ucfirst($entityFieldName);

        //Sprawdzamy czy stworzona metoda istnieje na danej encji, jeśli tak to odpalmy
        if (method_exists($entity, $getMethod)) {
            $address = $entity->$getMethod();
        }

        if ($address instanceof Address) {
            $globalAddress = $this->findOneBy([
                'entityName' => $this->getMappedEntityName($entity::class),
                'fieldName' => $entityFieldName,
                'entityId' => $entity->getId()
            ]);

            if ($globalAddress === null) {
                $globalAddress = new GlobalAddress();
            }

            // Jeśli nie ma nazwy to ustawiamy nazwę
            $name = $address->isInitialized('name') ? $address->getName() : '';
            if(empty($name)){
                if(property_exists($entity, 'name')){
                    $name = $entity->isInitialized('name') ?  $entity?->getName() : null;
                }

                if(empty($name)){
                    $name = $address->isInitialized('street') ?  $address?->getStreet() : null;
                }

                if (empty($name)) {
                    $name = '';
                }
            }

            $globalAddress
                ->setEntityName($this->getMappedEntityName($entity::class))
                ->setEntityId($entity->getId())
                ->setFieldName($entityFieldName)
                ->setName($name)
                ->setStreet($address->isInitialized('street') ? $address->getStreet() : null)
                ->setHouseNumber($address->isInitialized('houseNumber') ? $address->getHouseNumber() : null)
                ->setApartmentNumber($address->isInitialized('apartmentNumber') ? $address->getApartmentNumber() : null)
                ->setCity($address->isInitialized('city') ? $address->getCity() : null)
                ->setPostalCode($address->isInitialized('postalCode') ? $address->getPostalCode() : null)
                ->setCountryCode($address->isInitialized('countryCode') ? $address->getCountryCode() : null)
                ->setState($address->isInitialized('state') ? $address->getState() : null)
                ->setIsActive($isActive ?? null);

            $this->save($globalAddress, true);
        }
    }

    /**
     * @throws QueryFilterComparatorNotSupportedException
     * @throws FeatureNotImplemented
     */
    public function getGlobalAddressAsAddress(
        string $entityName,
        string $entityFieldName,
        int $entityId
    ): ?Address {
        $globalAddressArray = $this->getGlobalAddress($entityName, $entityFieldName, $entityId);

        if (empty($globalAddressArray)) {
            return null;
        }

        $address = new Address();
        $address
            ->setName($globalAddressArray['name'] ?? null)
            ->setStreet($globalAddressArray['street'] ?? null)
            ->setHouseNumber($globalAddressArray['houseNumber'] ?? null)
            ->setApartmentNumber($globalAddressArray['apartmentNumber'] ?? null)
            ->setCity($globalAddressArray['city'] ?? null)
            ->setPostalCode($globalAddressArray['postalCode'] ?? null)
            ->setCountryCode($globalAddressArray['countryCode'] ?? null)
            ->setState($globalAddressArray['state'] ?? null);

        return $address;
    }

    private function createGlobalAddressObject(array $address): GlobalAddress
    {
        return (new GlobalAddress())
            ->setId($address['id'])
            ->setEntityId((int)$address['entityId'])
            ->setEntityName($this->getMappedEntityName($address['entityName']))
            ->setFieldName($address['fieldName'])
            ->setName($address['name'])
            ->setStreet($address['street'])
            ->setHouseNumber($address['houseNumber'])
            ->setApartmentNumber($address['apartmentNumber'])
            ->setCity($address['city'])
            ->setPostalCode($address['postalCode'])
            ->setCountryCode($address['countryCode'])
            ->setState($address['state'])
            ->setIsActive($address['isActive']);
    }

    protected function getMappedEntityName(string $entityName): string
    {
        return $this->_em->getClassMetadata($entityName)->getTableName();
    }
}
