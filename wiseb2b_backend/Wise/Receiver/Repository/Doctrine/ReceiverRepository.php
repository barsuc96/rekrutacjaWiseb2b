<?php

declare(strict_types=1);

namespace Wise\Receiver\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Exception\QueryFilterComparatorNotSupportedException;
use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Repository\AbstractRepository;
use Wise\Core\Repository\Doctrine\GlobalAddressRepositoryInterface;
use Wise\Receiver\Domain\Receiver\Receiver;
use Wise\Receiver\Domain\Receiver\ReceiverRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Receiver>
 *
 * @method Receiver|null find($id, $lockMode = null, $lockVersion = null)
 * @method Receiver|null findOneBy(array $criteria, array $orderBy = null)
 * @method Receiver[]    findAll()
 * @method Receiver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceiverRepository extends AbstractRepository implements ReceiverRepositoryInterface
{
    protected const ENTITY_CLASS = Receiver::class;
    protected const DELIVERY_ADDRESS_ENTITY_FIELD_NAME = 'deliveryAddress';

    public function __construct(
        ManagerRegistry $registry,
        private readonly GlobalAddressRepositoryInterface $globalAddressRepository
    ) {
        parent::__construct($registry);
    }

    /**
     * @param Receiver $entity
     * @param bool $flush
     * @return Receiver
     */
    public function save(AbstractEntity $entity, bool $flush = false): AbstractEntity
    {
        $this->getEntityManager()->persist($entity);

        $this->globalAddressRepository->prepareAndSaveGlobalAddress(
            $entity,
            self::DELIVERY_ADDRESS_ENTITY_FIELD_NAME
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
        $addressFields = [];

        foreach ($fields as $fieldKey => $field) {
            if (str_starts_with($field, self::DELIVERY_ADDRESS_ENTITY_FIELD_NAME . '.')) {
                $addressFields[] = str_replace(self::DELIVERY_ADDRESS_ENTITY_FIELD_NAME . '.', '', $field);
                unset($fields[$fieldKey]);
            }
        }

        $receivers = parent::findByQueryFiltersView($queryFilters, $orderBy, $limit, $offset, $fields, $joins);

        $this->fetchAddresses($receivers, $addressFields);

        return $receivers;
    }

    /**
     * @throws QueryFilterComparatorNotSupportedException
     * @throws FeatureNotImplemented
     */
    private function fetchAddresses(array &$receivers, array $addressFields): void
    {
        if (empty($addressFields)) {
            return;
        }

        $receiversMapping = ArrayHelper::rearrangeKeysWithValuesUsingReferences($receivers);
        $receiversIdsList = array_keys($receiversMapping);
        $fieldsToFetch = [...$addressFields, 'entityId', 'fieldName'];

        $addresses = $this->globalAddressRepository->getGlobalAddressesForEntityIds(
            entityName: static::ENTITY_CLASS,
            entityFieldName: self::DELIVERY_ADDRESS_ENTITY_FIELD_NAME,
            entityIds: $receiversIdsList,
            fields: $fieldsToFetch
        );

        // przypisywanie wszystkich adresów do zamówień
        foreach ($addresses as &$address) {
            $entityId = $address['entityId'];
            $fieldName = $address['fieldName'];
            unset($address['entityId'], $address['fieldName']);
            $receiversMapping[$entityId][$fieldName] = $address;
        }

        $receivers = array_values($receiversMapping);
    }
}
