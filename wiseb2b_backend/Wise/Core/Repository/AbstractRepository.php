<?php

declare(strict_types=1);

namespace Wise\Core\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use InvalidArgumentException;
use Webmozart\Assert\Assert;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Enum\AggregateMethodEnum;
use Wise\Core\Exception\QueryFilterComparatorNotSupportedException;
use Wise\Core\Helper\QueryFilter\QueryBuilderHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\QueryJoin;
use Wise\Core\Model\Translation;
use Wise\Core\Model\Translations;
use Wise\Core\Service\SummaryField;

/**
 * @extends ServiceEntityRepository<self::ENTITY_CLASS>
 */
abstract class AbstractRepository extends ServiceEntityRepository implements RepositoryInterface
{
    protected const ENTITY_CLASS = '';

    /** @var array Wykorzystywana tabela do przechowywania wartości id'ków,
     * aby wykonywać takie same zapytania do bazy danych wykorzystując mechanizm Doctrine, który
     * zapamiętuję wynik takie samego zapytania i wyrzuca wynik "od razu"
     * Wykorzystywane w getAdditionalDataByIds i getAdditionalDataById;
     */
    protected array $additionalDataByIdsParamsCache;

    protected ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $registry, ?string $entity = null)
    {
        parent::__construct($registry, $entity ?? static::ENTITY_CLASS);
        $this->managerRegistry = $registry;
    }

    public function save(AbstractEntity $entity, bool $flush = false): AbstractEntity
    {
        $this->getEntityManager()->persist($entity);
        $this->persistTranslations($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function remove(AbstractEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        $this->removeTranslations($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws EntityNotFoundException
     * @throws ORMException
     */
    public function removeById(int $id): void
    {
        $entity = $this->getEntityManager()->getReference(static::ENTITY_CLASS, $id);

        if ($entity === null) {
            throw new EntityNotFoundException();
        }

        $this->removeTranslations($entity);

        // TODO: Czy na pewno zawsze chcemy flushować domyslnie zmiany? Raczej nie.
        $this->remove($entity);
    }

    /**
     * @throws QueryFilterComparatorNotSupportedException
     * @throws FeatureNotImplemented
     */
    public function findByQueryFilters(array $queryFilters, array $orderBy = null, $limit = null, $offset = null)
    {
        $alias = 't';
        $qb = $this->createQueryBuilder($alias)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->addOrderBy($alias . '.' . $orderBy['field'], $orderBy['direction']);

        QueryBuilderHelper::prepareByQueryFilters($qb, $queryFilters, $alias);

        return $qb->getQuery()->getResult();
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
     * UWAGA Otrzymane wyniki zamiast elementów złączonych kropką (.)  będą zwracały podkreślenia (_)
     *       Jest zakaz używania podkreśleń w nazwach pól!
     *
     * @param QueryFilter[] $queryFilters tablica filtrów do zapytania
     * @param array $orderBy tablica sortowania ['pole' => 'kierunek']
     * @param int|string|null $limit
     * @param int|string|null $offset
     * @param array|null $fields pola które mają być wyciągnięte przez selecta
     * @param QueryJoin[]|null $joins obiekty do joinowania z innymi tabelami
     * @param array|null $aggregates tablica stringów z nazwami agregatów które chcemy wyciągnąć
     * @return array
     * @throws FeatureNotImplemented
     * @throws QueryFilterComparatorNotSupportedException
     */
    public function findByQueryFiltersView(
        array $queryFilters,
        array $orderBy = null,
        $limit = null,
        $offset = null,
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
     * UWAGA Otrzymane wyniki zamiast elementów złączonych kropką (.)  będą zwracały podkreślenia (_)
     *       Jest zakaz używania podkreśleń w nazwach pól!
     *
     * @param QueryFilter[] $queryFilters tablica filtrów do zapytania
     * @param array $orderBy tablica sortowania ['pole' => 'kierunek']
     * @param int|string|null $limit
     * @param int|string|null $offset
     * @param array|null $fields pola które mają być wyciągnięte przez selecta
     * @param QueryJoin[]|null $joins obiekty do joinowania z innymi tabelami
     * @param array|null $aggregates tablica stringów z nazwami agregatów które chcemy wyciągnąć
     * @return array
     * @throws FeatureNotImplemented
     * @throws QueryFilterComparatorNotSupportedException
     */
    public function findByQueryFiltersViewWithLanguages(
        array $queryFilters,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        ?array $fields = [],
        ?array $joins = [],
        ?array $aggregates = [],
    ): array {
        $translationFields = null;
        $translationFieldsEntity = null;
        $currentLimit = $limit;
        $allFields = empty($fields);

        // Obsługa translacji
        $this->prepareTranslationToQuery($fields, $joins, $translationFields, $translationFieldsEntity, $limit);

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
        if($allFields){
            foreach ($fieldsEntityMetadata as $field => $type) {
                if(!isset($fields[$field])){
                    $fields[$field] = $field;
                }
            }
        }


        QueryBuilderHelper::prepareByQueryFilters($qb, $queryFilters, $alias, $fields, $joins, $fieldsEntityMetadata, $translationFields, $translationFieldsEntity);

        $query = $qb->getQuery();


        // ====== DEBUG ======
        /**
         * TODO Do usunięcia na koniec procesu developerskiego
         */
        $dql = $query->getDQL(); // debug
        $sql = $query->getSQL(); // debug
        $params = $query->getParameters(); // debug
        // ====== DEBUG ======

        $result = $query->getArrayResult();

        // Jeżeli pobierasz wszystkie pola encji przez gwiazdkę, to należy spłaszczyć wynik
        if (in_array('*', $fields, true)) {
            foreach ($result as &$item) {
                $item = [...$item, ...$item[0]];
                unset ($item[0]);
            }
        }


        // Przygotowanie wyniku (w związku, że używamy left join, to zwraca wiele tych samych rekordów, ale z innymi translacjami, więc je grupujemy)
        $preparedResult = [];

        foreach ($result as $entity) {
            $id = $entity['id'];

            // Inicjalizuje kategorię, jeśli jeszcze nie istnieje w wyniku
            if (!isset($preparedResult[$id])) {
                if($currentLimit !== null && count($preparedResult) == $currentLimit){
                    break;
                }

                $preparedResult[$id] = [];

                // Kopiuje wszystkie pola z encji do wyniku, poza polami tłumaczeń
                foreach ($entity as $key => $value) {
                    if (!in_array($key, ['translation_language', 'translation_name', 'translation_description']) && !str_starts_with($key, 'translation_')) {
                        $preparedResult[$id][$key] = $value;
                    }
                }

                // Inicjalizuje tablice tłumaczeń dla każdego pola tłumaczenia
                foreach ($translationFields as $field) {
                    $preparedResult[$id][$field] = [];
                }
            }

            // Dodaj tłumaczenia do odpowiednich tablic
            if(!empty($translationFields)){
                foreach ($translationFields as $field) {
                    $translationKey = 'translation_' . $field;
                    if (isset($entity[$translationKey]) && !isset($preparedResult[$id][$field][$entity['translation_language']])) {
                        $preparedResult[$id][$field][$entity['translation_language']] = [
                            'language' => $entity['translation_language'],
                            'translation' => $entity[$translationKey]
                        ];
                    }
                }
            }
        }


        if(!empty($translationFields)){
            foreach ($preparedResult as &$entity) {
                foreach ($translationFields as $field) {
                    $entity[$field] = array_values($entity[$field]);
                }
            }
        }

        // Zwracamy wynik
        return $preparedResult;
    }

    public function getTranslationClass(): string
    {
        return '';
    }

    public function getTranslationEntityIdField(): string
    {
        return '';
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        $entity = parent::find($id, $lockMode, $lockVersion);

        if(!empty($entity)){
            $this->addTranslations([$entity]);
        }

        return $entity;
    }

    public function findAll(): ?array
    {
        $entities = parent::findAll();

        return $entities;
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        $entity = parent::findOneBy($criteria, $orderBy);

        if(!empty($entity)){
            $this->addTranslations([$entity]);
        }

        return $entity;
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        $entities = parent::findBy($criteria, $orderBy, $limit, $offset);

        if(!empty($entities)){
            $this->addTranslations($entities);
        }

        return $entities;
    }

    /**
     * @return array
     */
    public function getAdditionalDataByIdsParamsCache(): array
    {
        return $this->additionalDataByIdsParamsCache;
    }

    /**
     * @param array $additionalDataByIdsParamsCache
     * @return $this
     */
    public function setAdditionalDataByIdsParamsCache(array $additionalDataByIdsParamsCache): self
    {
        $this->additionalDataByIdsParamsCache = $additionalDataByIdsParamsCache;

        return $this;
    }

    /**
     * Zwraca konkretną tablicę z wartościami dodatkowych danych dla podanego rekordu (przez id)
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getAdditionalDataById(int $id): array
    {
        if (in_array($id, $this->additionalDataByIdsParamsCache)) {
            return $this->getAdditionalDataByIds($this->additionalDataByIdsParamsCache)[$id];
        }

        return $this->getAdditionalDataByIds([$id])[$id];
    }

    /**
     * Funkcja do nadpisania w konkretnej instancji repozytorium
     * @param array $data
     * @param bool $overwriteCache
     * @return array
     * @throws FeatureNotImplemented
     */
    public function getAdditionalDataByIds(array $data, bool $overwriteCache = false): array
    {
        throw new FeatureNotImplemented(
            'Class: ' . static::class . ' must implement function: getAdditionalDataByIds(array $data): array.'
        );
    }

    /**
     * Metoda do zliczania obiektów według podanych kryteriów. Argumentem są tylko filtry, nie możemy tu podawać pól
     * ani joinów bo to zaburzy wynik zliczania, który ma być po prostu liczbą
     *
     * @param array $queryFilters
     * @param array $fields
     *
     * @return array|null
     * @throws FeatureNotImplemented
     * @throws QueryFilterComparatorNotSupportedException
     */
    public function aggregateByFilters(
        array $queryFilters, // np. [new QueryFilter('id', 1, QueryFilter::COMPARATOR_EQUAL)]
        array $fields
    ): ?array {
        $baseAlias = 't0';

        // creating query builder to use aggregate method on my field using query filters
        $qb = $this->createQueryBuilder($baseAlias);

        $first = true;
        /** @var SummaryField $field */
        foreach ($fields as $field) {
            // check if aggregate method is supported
            if (!in_array($field->getAggregateMethod(), AggregateMethodEnum::cases(), true)) {
                throw new InvalidArgumentException('Aggregate method not supported');
            }

            $fieldValue = $field->getAggregateMethod()->value .
                '(' . $baseAlias . '.' . $field->getAggregateField() . ') as ' . $field;

            if ($first) {
                $qb->select($fieldValue);
                $first = false;
            } else {
                $qb->addSelect($fieldValue);
            }
        }

        $fieldsEntityMetadata = $this->getFieldsAndTypes();

        QueryBuilderHelper::prepareFilters($qb, $queryFilters, $baseAlias, [], [], $fieldsEntityMetadata, null,null);

        $query = $qb->getQuery();
        $sqlDql = $query->getDQL(); // debug
        $sql = $query->getSQL(); // debug

        $result = $query->getArrayResult();

        return reset($result);
    }

    public function isExists(array $criteria): bool
    {
        $stmt = $this->getEntityManager()
            ->createQueryBuilder()
            ->from(static::ENTITY_CLASS, 'q')
            ->select(['q.id'])
            ->setMaxResults(1);
        foreach ($criteria as $field => $value) {
            $stmt->andWhere(sprintf('q.%s = :%s', $field, $field))
                ->setParameter($field, $value);
        }
        try {
            $stmt->getQuery()->getSingleScalarResult();

            return true;
        } catch (NoResultException) {
            return false;
        }
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
     * Pobiera wszystkie dostępne tłumaczenia i "wkłada je" do wskazanych Encji.
     *
     * @param list<AbstractEntity> $records Tablica Encji, do których chcemy dodać tłumaczenia
     *
     * @return void
     * @throws \InvalidArgumentException Tablica zawiera inne rekordy niż Encje lub różne Encje
     * @throws \ReflectionException Błąd podczas tworzenia refleksji Encji
     */
    protected function addTranslations(array $records): void
    {
        // Pomija, gdy nie ma Encji lub Encja nie posiada tłumaczeń
        if (empty($records) || !$this instanceof EntityWithTranslations) {
            return;
        }

        // Sprawdza, czy wszystkie rekordy są tą samą Encją
        Assert::allIsInstanceOf($records, reset($records)::class);

        // Pobiera nazwy pól z typem kolekcji Translations
        $translationFields = array_filter(
            (new \ReflectionClass(reset($records)))->getProperties(),
            static fn(\ReflectionProperty $p): bool => $p->getType()?->getName() === Translations::class
        );

        // Pobiera tłumaczenia z bazy danych
        $translations = $this->getEntityManager()
            ->createQueryBuilder()
            ->select([
                "q.{$this->getTranslationEntityIdField()} AS id",
                'q.language',
                ...array_map(fn(\ReflectionProperty $p): string => "q.{$p->getName()}", $translationFields),
            ])
            ->from($this->getTranslationClass(), 'q')
            ->where(
                $this->createQueryBuilder('t')->expr()->in(
                    "q.{$this->getTranslationEntityIdField()}",
                    array_map(fn(AbstractEntity $e): int => $e->getId(), $records)
                )
            )
            ->getQuery()
            ->getResult();

        // Tworzy tablicę asocjacyjną z ID jako klucz do wszystkich Encji
        $byId = [];
        foreach ($records as $record) {
            $byId[$record->getId()] = $record;
        }

        // Uzupełnia Encje danymi tłumaczeń
        foreach ($translations as $translation) {
            foreach ($translationFields as $field) {
                $getter = sprintf('get%s', ucfirst($field->getName()));
                if (!$byId[$translation['id']]->$getter() instanceof Translations) {
                    $field->setValue($byId[$translation['id']], new Translations());
                }

                if ($translation[$field->getName()] === null) {
                    continue;
                }

                $translationModel = (new Translation())
                    ->setLanguage($translation['language'])
                    ->setTranslation($translation[$field->getName()]);
                if ($byId[$translation['id']]->$getter()->contains($translationModel)) {
                    continue;
                }

                $byId[$translation['id']]->$getter()->add($translationModel);
            }
        }
    }

    /**
     * Aktualizuje wszystkie dostępne tłumaczenia w bazie danych.
     *
     * @param AbstractEntity $entity Encja z tłumaczeniami do aktualizacji
     *
     * @return void
     */
    protected function persistTranslations(AbstractEntity $entity): void
    {
        // Pomija, gdy Encja nie posiada tłumaczeń
        if (!$this instanceof EntityWithTranslations) {
            return;
        }

        // Pobiera nazwy pól z typem kolekcji Translations
        $translationFields = array_filter(
            (new \ReflectionClass($entity))->getProperties(),
            static fn(\ReflectionProperty $p): bool => $p->getType()?->getName() === Translations::class
        );

        // Pobiera aktualne tłumaczenia z bazy
        $translations = $this->getEntityManager()
            ->getRepository($this->getTranslationClass())
            ->findBy([$this->getTranslationEntityIdField() => $entity->getId()]);

        // Zamienia tablicę wartości na asocjacyjną i czyści tłumaczenia
        foreach ($translations as $key => $translation) {
            foreach ($translationFields as $field) {
                $setFieldName = sprintf('set%s', ucfirst($field->getName()));
                $translation->$setFieldName(null);
            }

            $translations[$translation->getLanguage()] = $translation;
            unset($translations[$key]);
        }

        // Uzupełnia modele bazodanowe danymi tłumaczeń
        $languagesToRemove = array_keys($translations);
        foreach ($translationFields as $field) {
            $getFieldName = sprintf('get%s', ucfirst($field->getName()));
            foreach ($entity->$getFieldName() ?? [] as $item) {
                $languagesToRemove = array_filter($languagesToRemove, fn($l) => $l !== $item->getLanguage());
                if (!isset($translations[$item->getLanguage()])) {
                    $translations[$item->getLanguage()] = new ($this->getTranslationClass())();
                    $setEntityIdField = sprintf('set%s', ucfirst($this->getTranslationEntityIdField()));
                    $translations[$item->getLanguage()]->$setEntityIdField($entity->getId());
                    $translations[$item->getLanguage()]->setLanguage($item->getLanguage());
                }
                $setFieldName = sprintf('set%s', ucfirst($field->getName()));
                $translations[$item->getLanguage()]->$setFieldName($item->getTranslation());
            }
        }

        // Wykonuje operacje bazodanowe
        foreach ($translations as $translation) {
            if (!in_array($translation->getLanguage(), $languagesToRemove, true)) {
                $translation->setIsActive(true);
                $this->getEntityManager()->persist($translation);
                continue;
            }
            $this->getEntityManager()->remove($translation);
        }
    }

    /**
     * Usuwa wszystkie dostępne tłumaczenia w bazie danych.
     *
     * @param AbstractEntity $entity Encja z tłumaczeniami do usunięcia
     *
     * @return void
     */
    protected function removeTranslations(AbstractEntity $entity): void
    {
        // Pomija, gdy Encja nie posiada tłumaczeń
        if (!$this instanceof EntityWithTranslations) {
            return;
        }

        // Wykonuje operacje bazodanowe
        $this->getEntityManager()
            ->createQueryBuilder()
            ->delete($this->getTranslationClass(), 'q')
            ->where("q.{$this->getTranslationEntityIdField()} = :entityId")
            ->getQuery()
            ->setParameter('entityId', $entity->getId())
            ->execute();
    }

    /**
     * Zwraca liczbę rekordów
     * @return int
     */
    public function countAll(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Zwraca nazwę obecnie obsługiwanej encji
     * @return string
     */
    public function getEntityClass(): string
    {
        return static::ENTITY_CLASS;
    }

    /**
     * Zwraca tablicę z nazwami pól i ich typami
     * @return array
     */
    protected function getFieldsAndTypes()
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
}
