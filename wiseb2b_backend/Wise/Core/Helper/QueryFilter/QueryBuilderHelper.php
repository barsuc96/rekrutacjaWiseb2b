<?php

declare(strict_types=1);

namespace Wise\Core\Helper\QueryFilter;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use PDO;
use Symfony\Component\HttpFoundation\Response;
use Wise\Core\Exception\QueryFilterComparatorNotSupportedException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\QueryJoin;

class QueryBuilderHelper
{
    /**
     * Metoda służy to budowy zapytania doctrinowego na podstawie tablicy obiektów QueryFilter
     *
     * @param QueryBuilder $queryBuilder
     * @param QueryFilter[] $queryFilters
     * @param string $tableAlias
     * @param array $fields
     * @param ?QueryJoin[] $joins
     * @return void
     * @throws FeatureNotImplemented
     * @throws QueryFilterComparatorNotSupportedException
     */
    public static function prepareByQueryFilters(
        QueryBuilder &$queryBuilder,
        array $queryFilters,
        string $baseTableAlias,
        ?array $fields = null,
        ?array $joins = null,
        ?array $fieldsEntityMetadata = null,
        ?array $translationFields = null,
        ?string $translationFieldsEntity = null,
    ): void {
        self::cutUnnecessaryAliases($fields);

        self::prepareDateWhereByQueryFilters($queryBuilder, $queryFilters, $baseTableAlias);

        self::prepareJoins($queryBuilder, $joins, $baseTableAlias);

        self::prepareFields($queryBuilder, $fields, $baseTableAlias, $joins);

        self::prepareFilters($queryBuilder, $queryFilters, $baseTableAlias, $joins, $fields, $fieldsEntityMetadata ?? null, $translationFields, $translationFieldsEntity);
    }

    /**
     * Metoda służy to budowy zapytania doctrinowego na podstawie tablicy obiektów QueryFilter,
     * dla pojedynczego obiektu
     *
     * @throws FeatureNotImplemented
     */
    public static function prepareObjectByQueryFilters(
        QueryBuilder $queryBuilder,
        string $baseTableAlias,
        ?array $fields = null,
        ?array $joins = null
    ): void {
        self::cutUnnecessaryAliases($fields);

        self::prepareJoins($queryBuilder, $joins, $baseTableAlias);

        self::prepareItemFields($queryBuilder, $fields, $baseTableAlias, $joins);
    }

    private static function cutUnnecessaryAliases(?array &$fields): void
    {
        if ($fields === null) {
            return;
        }
        foreach ($fields as & $field) {
            if (str_starts_with($field, 't0.')) {
                $field = substr($field, 3);
            }
        }
    }

    public static function prepareFilters(
        QueryBuilder $queryBuilder,
        ?array $queryFilters,
        ?string $baseTableAlias,
        ?array $joins,
        ?array $fields,
        ?array $fieldsEntityMetadata,
        ?array $translationFields,
        ?string $translationFieldsEntity
    ): void {

        // Robimy kopie filtrów, ponieważ poniższy kod na polach jonowanych np. 'defaultDeliveryMethodId.idExternal' usuwa przedrostek (w tym przypadku 'defaultDeliveryMethodId') co powoduje później problemy.
        // A w związku, że QueryFilter to obiekt i jest do niego referencja to musimy zrobić kopie bo potrzebujemy tych danych do m.in wyliczenia total count dla AdminApi czy paginacji w ApiUi
        $copyFilters = [];
        foreach ($queryFilters as $q) {
            $copyFilters[] = new QueryFilter($q->getField(), $q->getValue(), $q->getComparator(), $q->getFieldsInTable());
        }


        /**
         * @var QueryFilter $queryFilter
         */
        foreach ($copyFilters as $key => $queryFilter) {
            $tableAlias = $baseTableAlias;

            if (is_array($joins)) {
                foreach ($joins as $join) {
                    if (str_starts_with($queryFilter->getField(), $join->getAlias() . '.')) {
                        $tableAlias = null;
                    }

                    if (
                        count($fieldData = explode('.', $queryFilter->getField())) > 1
                        &&
                        (isset($join->getFields()[$fieldData[0]]))
                    ) {
                        $tableAlias = $join->getAlias();
                        $queryFilter->setField($fieldData[1]);
                    } elseif (isset($join->getFields()[$queryFilter->getField()])) {
                        if (isset($fields[$queryFilter->getField()])) {
                            $fieldData = explode('.', $fields[$queryFilter->getField()]);
                            if (count($fieldData) > 1 && $fieldData[0] === $queryFilter->getField()) {
                                $tableAlias = $join->getAlias();
                                $queryFilter->setField($fieldData[1]);
                            }
                        }
                    } else {
                        foreach ($join->getFields() as $field) {
                            if (
                                (count($joinFieldData = explode('.', $field)) > 0)
                                &&
                                (count($queryFilterFieldData = explode('.', $queryFilter->getField())) > 0)
                                &&
                                ($joinFieldData[0] == $queryFilterFieldData[0])
                            ) {
                                $tableAlias = $join->getAlias();
                                $queryFilter->setField($fieldData[1]);
                            }
                        }
                    }
                }
            }

            if (str_starts_with($queryFilter->getField(), $baseTableAlias . '.')) {
                $tableAlias = null;
            }

            $field = (is_null($tableAlias) ? '' : $tableAlias . '.') . $queryFilter->getField();

            switch ($queryFilter->getComparator()) {
                case QueryFilter::COMPARATOR_EQUAL:
                    self::prepareFilterWithEqualComparator($queryBuilder, $queryFilter, $field);
                    break;
                case QueryFilter::COMPARATOR_NOT_EQUAL:
                    self::prepareFilterWithNotEqualComparator($queryBuilder, $queryFilter, $field);
                    break;
                case QueryFilter::COMPARATOR_IN:
                    self::prepareFilterWithInComparator($queryBuilder, $queryFilter, $field);
                    break;
                case QueryFilter::COMPARATOR_NOT_IN:
                    self::prepareFilterWithNotInComparator($queryBuilder, $queryFilter, $field);
                    break;
                case QueryFilter::COMPARATOR_GREATER_THAN:
                    self::prepareFilterWithGreaterThanComparator($queryBuilder, $queryFilter, $field);
                    break;
                case QueryFilter::COMPARATOR_GREATER_THAN_OR_EQUAL:
                    self::prepareFilterWithGreaterThanOrEqualComparator($queryBuilder, $queryFilter, $field);
                    break;
                case QueryFilter::COMPARATOR_LESS_THAN:
                    self::prepareFilterWithLessThanComparator($queryBuilder, $queryFilter, $field);
                    break;
                case QueryFilter::COMPARATOR_LESS_THAN_OR_EQUAL:
                    self::prepareFilterWithLessThanOrEqualComparator($queryBuilder, $queryFilter, $field);
                    break;
                case QueryFilter::COMPARATOR_CONTAINS:
                    self::prepareFilterWithLikeComparator($queryBuilder, $queryFilter, $tableAlias, $fieldsEntityMetadata, $translationFields, $joins, $translationFieldsEntity);
                    break;
                case QueryFilter::COMPARATOR_STARTS_WITH:
                    throw new FeatureNotImplemented(
                        'filtr %? (rozpoczyna się frazą) jeszcze nie został zaimplementowany',
                        Response::HTTP_NOT_IMPLEMENTED
                    );
                    break;
                case QueryFilter::COMPARATOR_ENDS_WITH:
                    throw new FeatureNotImplemented(
                        'filtr ?% (kończy się frazą) jeszcze nie został zaimplementowany',
                        Response::HTTP_NOT_IMPLEMENTED
                    );
                    break;
                case QueryFilter::COMPARATOR_IS_NULL:
                    self::prepareFilterIsNullComparator($queryBuilder, $queryFilter, $tableAlias);
                    break;
                case QueryFilter::COMPARATOR_IS_NOT_NULL:
                    self::prepareFilterIsNotNullComparator($queryBuilder, $queryFilter, $tableAlias);
                    break;
                default:
                    throw new QueryFilterComparatorNotSupportedException($queryFilter->getComparator());
            }
        }
    }

    private static function prepareJoins(QueryBuilder $queryBuilder, ?array $joins, string $baseTableAlias): void
    {
        if (!is_null($joins)) {
            foreach ($joins as $join) {
                $joinCondition = '';
                foreach ($join->getFields() as $leftTableField => $rightTableField) {
                    $rightTableData = explode('.', $rightTableField);
                    $leftTableData = explode('.', $leftTableField);
                    if (count($rightTableData) === 2) {
                        $join->setAlias($rightTableData[0] . '1');
                        $rightTableField = $rightTableData[1];
                    }

                    if (count($leftTableData) === 2) {
                        $leftTableField = $leftTableData[1];
                    }

                    $alias = $baseTableAlias . '.';
                    foreach ($joins as $joinAlias) {
                        if (count($leftTableData) > 1 && $leftTableData[0] . '1' === $joinAlias->getAlias()) {
                            $alias = $joinAlias->getAlias() . '.';
                            break;
                        }

                        if (str_starts_with($leftTableField, $joinAlias->getAlias() . '.')) {
                            $alias = '';
                            break;
                        }
                    }

                    $joinCondition .=
                        $alias . $leftTableField . ' = ' . $join->getAlias() . '.' . $rightTableField;
                }

                switch ($join->getType()) {
                    case QueryJoin::JOIN_TYPE_INNER:
                        $queryBuilder->innerJoin(
                            $join->getEntityClass(),
                            $join->getAlias(),
                            Join::WITH,
                            $joinCondition
                        );
                        break;
                    case QueryJoin::JOIN_TYPE_LEFT:
                        $queryBuilder->leftJoin($join->getEntityClass(), $join->getAlias(), Join::WITH, $joinCondition);
                        break;
                    default:
                        throw new FeatureNotImplemented(
                            'Join with type: ' . $join->getType() . ' is not implemented yet'
                        );
                }
            }
        }
    }

    private static function prepareItemFields(
        QueryBuilder $qb,
        ?array $fields,
        ?string $baseTableAlias,
        ?array $joins
    ): void {
        // Przygotowujemy pola do selecta
        if (!is_null($fields)) {
            $selectFields = [];

            // Dla każdego pola
            foreach ($fields as $key => $field) {
                $fieldAlias = $field;
                $alias = $baseTableAlias;
                if (str_contains($field, '.')) {
                    $fieldNameData = explode('.', $field);
                    if (count($fieldNameData) > 1) {
                        $field = $fieldNameData[1];
                        $alias = $fieldNameData[0] . '1';
                    }
                } else {
                    /** @var QueryJoin $join */
                    foreach ($joins as $join) {
                        if (isset($join->getFields()[$key])) {
                            $alias = $join->getAlias();
                            $fieldNameData = explode('.', $field);
                            if (count($fieldNameData) > 1) {
                                $field = $fieldNameData[1];
                            }
                        }
                    }
                }

                $selectFields[] = $alias . '.' . $field . ' as ' . str_replace('.', '_', $fieldAlias);
            }

            $qb->addSelect($selectFields);
        }
    }

    private static function prepareFields(
        QueryBuilder $qb,
        ?array $fields,
        ?string $baseTableAlias,
        ?array $joins
    ): void {
        if (!$fields) {
            return;
        }

        $selectFields = [];

        // Dla każdego pola
        foreach ($fields as $key => $field) {
            $fieldAlias = $field;
            $alias = $baseTableAlias;

            // Jeżeli jest gwiazdka, to wyciągnij cały rekord z bazy (uwaga, rekord będzie w dedykowanej tabeli)
            // @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.15/reference/dql-doctrine-query-language.html#result-format
            if ($field === '*') {
                $selectFields[] = $alias;
                continue;
            }

            // Jeżeli alias dotyczy zliczania
            if (str_contains($field, 'count(')) {
                $selectFields[] = $field . ' as count';
                continue;
            }

            // Dla pól złączeniowych - zawierających kropkę w nazwie, inaczej wyciągamy alias pola i nazwę
            // Rozbijamy tutaj pole złączeniowe
            if (str_contains($field, '.')) {
                $fieldNameData = explode('.', $field);
                if (count($fieldNameData) > 1) {
                    $field = $fieldNameData[1];
                    $alias = $fieldNameData[0] . '1';
                }
            }

            $selectFields[] = $alias . '.' . $field . ' as ' . str_replace('.', '_', $fieldAlias);
        }

        $qb->select($selectFields);
    }

    public static function prepareFilterWithEqualComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field
    ): void {
        $queryBuilder
            ->andWhere($field . ' = :p' . md5(serialize($queryFilter)))
            ->setParameter(
                'p' . md5(serialize($queryFilter)),
                $queryFilter->getValue(),
                is_string($queryFilter->getValue()) ? PDO::PARAM_STR : null
            );
    }

    public static function prepareFilterWithNotEqualComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field
    ): void {
        $queryBuilder
            ->andWhere($field . ' != :p' . md5(serialize($queryFilter)))
            ->setParameter(
                'p' . md5(serialize($queryFilter)),
                $queryFilter->getValue(),
                is_string($queryFilter->getValue()) ? PDO::PARAM_STR : null
            );
    }

    /**
     * Metoda służąca do dodania warunku typu większy od. np. date > '2023-05-06'
     * @param QueryBuilder $queryBuilder
     * @param QueryFilter $queryFilter
     * @param string $field
     * @return QueryBuilder
     */
    public static function prepareFilterWithGreaterThanComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field
    ): QueryBuilder {
        return QueryBuilderHelper::prepareFilterWithSimpleComparator(
            $queryBuilder,
            $queryFilter,
            $field,
            QueryFilter::COMPARATOR_GREATER_THAN
        );
    }

    /**
     * Metoda służąca do dodania warunku typu mniejszy od. np. date < '2023-05-06'
     * @param QueryBuilder $queryBuilder
     * @param QueryFilter $queryFilter
     * @param string $field
     * @return QueryBuilder
     */
    public static function prepareFilterWithLessThanComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field
    ): QueryBuilder {
        return QueryBuilderHelper::prepareFilterWithSimpleComparator(
            $queryBuilder,
            $queryFilter,
            $field,
            QueryFilter::COMPARATOR_LESS_THAN
        );
    }

    /**
     * Metoda służąca do dodania warunku typu większy od lub równy. np. date => '2023-05-06'
     * @param QueryBuilder $queryBuilder
     * @param QueryFilter $queryFilter
     * @param string $field
     * @return QueryBuilder
     */
    public static function prepareFilterWithGreaterThanOrEqualComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field
    ): QueryBuilder {
        return QueryBuilderHelper::prepareFilterWithSimpleComparator(
            $queryBuilder,
            $queryFilter,
            $field,
            QueryFilter::COMPARATOR_GREATER_THAN_OR_EQUAL
        );
    }

    /**
     * Metoda służąca do dodania warunku typu mniejszy od lub równy. np. date =< '2023-05-06'
     * @param QueryBuilder $queryBuilder
     * @param QueryFilter $queryFilter
     * @param string $field
     * @return QueryBuilder
     */
    public static function prepareFilterWithLessThanOrEqualComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field
    ): QueryBuilder {
        return QueryBuilderHelper::prepareFilterWithSimpleComparator(
            $queryBuilder,
            $queryFilter,
            $field,
            QueryFilter::COMPARATOR_LESS_THAN_OR_EQUAL
        );
    }

    /**
     * Metoda służąca do dodania dodatkowego warunku Where (filtra) do bulidera z warunkami LIKE dla poszczególnych pól
     * wyszukiwania, warunki dla poszczególnych pól oddzielone OR
     */
    public static function prepareFilterWithLikeComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $tableAlias,
        ?array $fieldsEntityMetadata,
        ?array $translationFields,
        ?array $joins,
        ?string $translationFieldsEntity
    ): void {
        $isStringValues = false;
        $isIntValues = false;
        $isBoolValues = false;
        $translationAlias = null;

        $key = md5(serialize($queryFilter));
        $param = 'filter_' . $key . '_' . $queryFilter->getField();
        $paramInteger = 'filter_int_' . $key . '_' . $queryFilter->getField();
        $paramBoolean = 'filter_bool_' . $key . '_' . $queryFilter->getField();


        $expr = $queryBuilder->expr();
        $orX = $expr->orX();

        // Weryfikacja w Join jaki wygenerował się alias na klasy translacji
        if($translationFieldsEntity !== null){
            /** @var QueryJoin $join */
            foreach ($joins as $join){
                if($join->getEntityClass() === $translationFieldsEntity){
                    $translationAlias = $join->getAlias();
                }
            }
        }

        foreach ($queryFilter->getFieldsInTable() as $field) {
            if(isset($fieldsEntityMetadata[$field])){
                $fieldType = $fieldsEntityMetadata[$field];

                // Zabezpieczenie w sytuacji, kiedy wartość przekracza wartość int np. gdy podajemy w searchkeyword: EAN
                if($fieldType === 'integer' && is_string($queryFilter->getValue()) && preg_match('/^-?\d+$/', $queryFilter->getValue())){
                    $intValue = floatval($queryFilter->getValue());

                    // W związku, że typ zmiennej po której chcemy filtrować jest intem a wartość po której chemy szukać jest również intem ale wykraczającym po za zakres inta
                    if ($intValue > 2147483647 || $intValue < -2147483648) {
                        continue;
                    }
                }

                switch ($fieldType) {
                    case 'text':
                    case 'string':
                        $isStringValues = true;
                        $orX->add(
                            $expr->like(
                                $expr->lower($tableAlias . '.' . $field),
                                ':' .$param
                            )
                        );
                        break;

                    case 'integer':
                        $isIntValues = true;
                        $orX->add($expr->eq($tableAlias . '.' . $field, ':' . $paramInteger));
                        break;

                    case 'boolean':
                        $isBoolValues = true;
                        $orX->add($expr->eq($tableAlias . '.' . $field, ':' . $paramBoolean));
                        break;
                }
            }else{

                if(!empty($translationFields) && in_array($field, $translationFields) && $translationAlias !== null){
                    $isStringValues = true;
                    $orX->add(
                        $expr->like(
                            $expr->lower($translationAlias . '.' . $field),
                            ':' .$param
                        )
                    );
                    continue;
                }


                // Obsługa pól które są z innych tabel
                // Jeśli pole zawiera kropkę, to znaczy, że jest to pole z innej tabeli
                if(str_contains($field, '.')){
                    $fieldData = explode('.', $field);
                    $field = $fieldData[1];
                    $tableAlias = $fieldData[0];
                    $foundAlias = false;

                    // Sprawdzamy czy alias z pola znajduje się w tablicy aliasów z joinów
                    foreach ($joins as $key => $join) {
                        if ($key === $tableAlias) {

                            // Jeśli alias z pola znajduje się w tablicy aliasów z joinów, to ustawiamy alias na ten z joina
                            $tableAlias = $join->getAlias();
                            $foundAlias = true;
                            break;
                        }
                    }

                    // Jeśli nie znaleziono aliasu z joina, to pomijamy to pole
                    if(!$foundAlias){
                        continue;
                    }

                    // Dodaje warunek dla pola z innej tabeli
                    $isStringValues = true;
                    $orX->add(
                        $expr->like(
                            $expr->lower($tableAlias . '.' . $field),
                            ':' .$param
                        )
                    );
                    continue;
                }


                $isStringValues = true;
                $orX->add($expr->like($tableAlias . '.' . $field, ':' . $param));
            }
        }

        $queryBuilder->andWhere($orX);

        if($isStringValues){
            $queryBuilder->setParameter($param, '%' . strtolower($queryFilter->getValue()) . '%');
        }

        if($isIntValues){
            $queryBuilder->setParameter($paramInteger, intval($queryFilter->getValue()));
        }

        if($isBoolValues){
            $queryBuilder->setParameter($paramBoolean, boolval($queryFilter->getValue()));
        }
    }

    public static function prepareFilterWithInComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field
    ): QueryBuilder {
        $queryBuilder
            ->andWhere($field . ' IN (:p' . md5($queryFilter->getField()) . ')')
            ->setParameter(
                'p' . md5($queryFilter->getField()),
                $queryFilter->getValue(),
                ArrayParameterType::INTEGER
            );

        return $queryBuilder;
    }

    public static function prepareFilterWithNotInComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field
    ): QueryBuilder {
        $queryBuilder
            ->andWhere($field . ' NOT IN (:p' . md5($queryFilter->getField()) . ')')
            ->setParameter(
                'p' . md5($queryFilter->getField()),
                $queryFilter->getValue(),
                ArrayParameterType::INTEGER
            );

        return $queryBuilder;
    }


    /**
     * Metoda do transformacji standardowych filtrów po zakresie dat modyfikacji obiektu w obiekt queryBuilder
     *
     * @param QueryBuilder $queryBuilder
     * @param QueryFilter[] $queryFilters
     * @param string $tableAlias
     * @return void
     */
    public static function prepareDateWhereByQueryFilters(
        QueryBuilder $queryBuilder,
        array &$queryFilters,
        string $tableAlias
    ): void {
        foreach ($queryFilters as $key => $queryFilter) {
            if ($queryFilter->getField() === 'changeDateFrom') {
                $queryBuilder->andWhere(
                    '(' .
                    $tableAlias . '.sysUpdateDate >= :filter_' . $key . '_' . $queryFilter->getField() .
                    ' OR ('
                    . $tableAlias . '.sysUpdateDate is null AND ' .
                    $tableAlias . '.sysInsertDate >= :filter_' . $key . '_' . $queryFilter->getField() .
                    ')' .
                    ')'
                )
                    ->setParameter('filter_' . $key . '_' . $queryFilter->getField(), $queryFilter->getValue());
                unset($queryFilters[$key]);
            } elseif ($queryFilter->getField() === 'changeDateTo') {
                $queryBuilder->andWhere(
                    '(' .
                    $tableAlias . '.sysUpdateDate <= :filter_' . $key . '_' . $queryFilter->getField() .
                    ' OR ('
                    . $tableAlias . '.sysUpdateDate is null AND ' .
                    $tableAlias . '.sysInsertDate <= :filter_' . $key . '_' . $queryFilter->getField() .
                    ')' .
                    ')'
                )
                    ->setParameter('filter_' . $key . '_' . $queryFilter->getField(), $queryFilter->getValue());
                unset($queryFilters[$key]);
            }
        }
    }

    public static function prepareFilterIsNullComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field
    ): QueryBuilder {
        $queryBuilder
            ->andWhere($field . '.' . $queryFilter->getField() . ' ' . QueryFilter::COMPARATOR_IS_NULL);

        return $queryBuilder;
    }

    public static function prepareFilterIsNotNullComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field
    ): QueryBuilder {
        $queryBuilder
            ->andWhere($field . '.' . $queryFilter->getField() . ' ' . QueryFilter::COMPARATOR_IS_NOT_NULL);

        return $queryBuilder;
    }

    /**
     * Meotda służąca do dodania warunku typu mniejszy od, np: date < '2023-05-06'
     */
    protected static function prepareFilterWithSimpleComparator(
        QueryBuilder $queryBuilder,
        QueryFilter $queryFilter,
        string $field,
        string $comparatorSymbol,
    ): QueryBuilder {
        $queryBuilder
            ->andWhere($field . ' ' . $comparatorSymbol . ' :p' . md5(serialize($queryFilter)))
            ->setParameter(
                'p' . md5(serialize($queryFilter)),
                $queryFilter->getValue(),
                is_string($queryFilter->getValue()) ? PDO::PARAM_STR : null
            );

        return $queryBuilder;
    }

}
