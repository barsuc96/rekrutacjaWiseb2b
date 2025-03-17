<?php

declare(strict_types=1);

namespace Wise\Core\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Doctrine\Persistence\ManagerRegistry;
use Wise\Core\Domain\Admin\ReplicationObject\ReplicationObject;
use Wise\Core\Exception\QueryFilterComparatorNotSupportedException;
use Wise\Core\Helper\QueryFilter\QueryBuilderHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\QueryJoin;
use Wise\Core\Model\Translations;
use Wise\Core\Service\CommonListParams;

/**
 * @extends ServiceEntityRepository<ReplicationObject>
 *
 * @method ReplicationObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReplicationObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReplicationObject[]    findAll()
 * @method ReplicationObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * Nie dziedziczymy po abstract repository, ponieważ nie chcemy, aby zmiany w nim wpływały na ten mechanizm
 */
class ReplicationObjectRepository extends ServiceEntityRepository implements ReplicationObjectRepositoryInterface
{
    public const ENTITY_CLASS = ReplicationObject::class;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReplicationObject::class);
    }

    public function save(ReplicationObject $entity, bool $flush = false): int
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity->getId();
    }


    public function removeByRequestId(int $requestId)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->delete()
            ->where('t.idRequest = :requestId')
            ->setParameter('requestId', $requestId);

        $query = $qb->getQuery();
        $query->execute();
    }

    /**
     * UWAGA Otrzymane wyniki zamiast elementów złączonych kropką (.)  będą zwracały podkreślenia (_)
     *       Jest zakaz używania podkreśleń w nazwach pól!
     *
     * @param QueryFilter[] $queryFilters tablica filtrów do zapytania
     * @param array|null $orderBy tablica sortowania ['pole' => 'kierunek']
     * @param int|string|null $limit
     * @param int|string|null $offset
     * @param array|null $fields pola które mają być wyciągnięte przez selecta
     * @param QueryJoin[]|null $joins obiekty do joinowania z innymi tabelami
     * @param array|null $aggregates tablica stringów z nazwami agregatów które chcemy wyciągnąć
     * @return array
     * @throws FeatureNotImplemented
     */
    public function findByQueryFiltersView(
        array $queryFilters,
        array $orderBy = null,
        int|string $limit = null,
        int|string $offset = null,
        ?array $fields = [],
        ?array $joins = [],
        ?array $aggregates = [],
    ): array {
        foreach ($aggregates ?? [] as $aggregate) {
            foreach ($fields as $fieldKey => $field) {
                if ($field === $aggregate) {
                    unset($fields[$fieldKey]);
                    continue;
                }

                if (str_starts_with($field . '.', $aggregate)) {
                    unset($fields[$fieldKey]);
                    continue;
                }
            }
        }
        $alias = 't0';
        $qb = $this->createQueryBuilder($alias)
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if (!is_null($orderBy)) {
            $qb->addOrderBy($alias . '.' . $orderBy['field'], $orderBy['direction']);
        }

        $fieldsEntityMetadata = $this->getFieldsAndTypes();

        QueryBuilderHelper::prepareByQueryFilters($qb, $queryFilters, $alias, $fields, $joins, $fieldsEntityMetadata);

        $query = $qb->getQuery();

        /**
         * TODO Do usunięcia na koniec procesu developerskiego
         */
        $dql = $query->getDQL(); // debug
        $sql = $query->getSQL(); // debug
        $params = $query->getParameters(); // debug

        $result = $query->getArrayResult();

        // Jeżeli pobierasz wszystkie pola encji przez gwiazdkę, to należy spłaszczyć wynik
        if (in_array('*', $fields, true)) {
            foreach ($result as &$item) {
                $item = [...$item, ...$item[0]];
                unset ($item[0]);
            }
        }

        return $result;
    }

    /**
     * Zwraca tablicę z nazwami pól i ich typami
     * @return array
     */
    protected function getFieldsAndTypes(): array
    {
        $metadata = $this->getEntityManager()->getClassMetadata($this->_entityName);
        $fieldMappings = $metadata->getFieldNames();
        $fieldTypes = [];

        foreach ($fieldMappings as $fieldName) {
            $fieldType = $metadata->getTypeOfField($fieldName);
            $fieldTypes[$fieldName] = $fieldType;
        }

        return $fieldTypes;
    }

    public function getTranslationClass(): string
    {
        return '';
    }

    public function getTranslationEntityIdField(): string
    {
        return '';
    }

    /**
     * Zwraca ilość wszystkich rekordów na podstawie danych QueryFilters
     * @param array $queryFilters
     * @param array|null $joins
     * @param string $countField
     * @return int
     * @throws FeatureNotImplemented
     */
    public function getTotalCountByQueryFilters(
        array $queryFilters,
        ?array $joins = [],
        string $countField = 'id'
    ): int {
        $translationFields = null;
        $translationFieldsEntity = null;
        $limit = null;
        $fields = [];

        // Obsługa translacji
        $this->prepareTranslationToQuery($fields, $joins, $translationFields, $translationFieldsEntity, $limit);

        $alias = 't0';
        $qb = $this->createQueryBuilder($alias)
            ->setMaxResults(null)
            ->setFirstResult(null);

        if(empty($translationFields)){
            $fields = ['count(' . $alias . '.' . $countField . ')'];
        }else{
            $fields = ['count(distinct ' . $alias . '.' . $countField . ')'];
        }

        $fieldsEntityMetadata = $this->getFieldsAndTypes();

        QueryBuilderHelper::prepareByQueryFilters($qb, $queryFilters, $alias, $fields, $joins, $fieldsEntityMetadata, $translationFields, $translationFieldsEntity);

        $query = $qb->getQuery();

        return $query->getArrayResult()[0]['count'];
    }

    /**
     * Przygotowuje Query pod translacje
     * Dodaje join do translacji, pola translacji oraz pola id
     * @param array|null $fields
     * @param array|null $joins
     * @param array|null $translationFields
     * @param array|null $translationFieldsEntity
     * @return void
     */
    protected function prepareTranslationToQuery(?array &$fields,?array &$joins, ?array &$translationFields, ?array &$translationFieldsEntity, ?int &$limit): void
    {
        // Obsługa translacji
        if(!empty($this->getTranslationClass()) && !empty($this->getTranslationEntityIdField())){
            $translationFieldsEntity = $this->getTranslationClass();

            // Pobiera pola tłumaczeń
            $translationFields = array_map(
                fn(\ReflectionProperty $p): string => $p->getName(),
                array_filter(
                    (new \ReflectionClass(static::ENTITY_CLASS))->getProperties(),
                    static fn(\ReflectionProperty $p): bool => $p->getType()?->getName() === Translations::class
                )
            );

            if(!empty($translationFields)){
                $limit = null;
                $fields['translation.language'] = 'translation.language';
                $joins[] = new QueryJoin($this->getTranslationClass(), 'translation', ['id' => 'translation.' . $this->getTranslationEntityIdField()], QueryJoin::JOIN_TYPE_LEFT);
                $fields = array_flip($fields);
                foreach ($translationFields as $translationField){
                    $fields['translation.' . $translationField] = 'translation.' . $translationField;
                    unset($fields[$translationField]);
                }
                $fields = array_flip($fields);
            }

            if(!empty($fields) && !in_array('id', $fields)){
                $fields['id'] = 'id';
            }
        }
    }



    /**
     * Metoda do pobrania szczegółowych danych dla danej tabeli,
     *
     * Metoda zwraza listę pól z modelu biznesowego,
     *
     * dodakowo, jeśli $additionalFields jest wypełnione, to wysyłamy dodatkowe powiązane pola z tej listy
     *
     * @throws FeatureNotImplemented
     * @throws QueryFilterComparatorNotSupportedException
     */
    public function getByIdView(
        ?array $filters = [],
        ?array $fields = [],
        ?array $joins = [],
        ?array $aggregates = [],
    ): ?array {
        $result = $this->findByQueryFiltersView(
            queryFilters: $filters,
            fields: $fields,
            joins: $joins,
            aggregates: $aggregates
        );

        if (count($result) > 0) {
            return reset($result);
        }

        return [];
    }

    /**
     * Zwraca nazwę obecnie obsługiwanej encji
     * @return string
     */
    public function getEntityClass(): string
    {
        return static::ENTITY_CLASS;
    }

    public function getStatistics(\DateTime $startDate, \DateTime $endDate, ?int $status = null): array
    {
        $dql = '
        SELECT r.responseStatus AS status, r.objectClass AS class, COUNT(r.id) AS count
        FROM ' . ReplicationObject::class . ' r
        WHERE r.sysInsertDate BETWEEN :startDate AND :endDate
    ';

        if ($status !== null) {
            $dql .= ' AND r.responseStatus = :status';
        }

        $dql .= '
        GROUP BY r.responseStatus, r.objectClass
        ORDER BY r.objectClass, r.responseStatus
    ';

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('startDate', $startDate);
        $query->setParameter('endDate', $endDate);

        if ($status !== null) {
            $query->setParameter('status', $status);
        }

        return $query->getResult();
    }

    public function removeOrphans(): void
    {
        $qb = $this->createQueryBuilder('r')
            ->delete()
            ->where('r.idRequest NOT IN (SELECT rq.id FROM Wise\Core\Domain\Admin\ReplicationRequest\ReplicationRequest rq)');

        $qb->getQuery()->execute();

    }

}
