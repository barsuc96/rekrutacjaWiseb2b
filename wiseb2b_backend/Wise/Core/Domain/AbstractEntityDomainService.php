<?php

namespace Wise\Core\Domain;

use ReflectionClass;
use Wise\Core\Domain\Interfaces\EntityDomainServiceInterface;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Helper\QueryFilter\QueryJoinsHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Repository\RepositoryInterface;

/**
 * Klasa bazowa dla serwisów domenowych
 */
abstract class AbstractEntityDomainService implements EntityDomainServiceInterface
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private ?string $notFoundException = ObjectNotFoundException::class,
        private readonly ?EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper = null
    ){}

    /**
     * Zwraca nazwę aktualnej encji
     * @return string
     */
    public function getCurrentEntityName(): string
    {
        return $this->repository->getEntityClass();
    }

    /**
     * Zwraca informacje czy istnieje encja spełniająca podane kryteria
     * @param array $criteria
     * @return bool
     */
    public function isExists(array $criteria): bool
    {
        return $this->repository->isExists($criteria);
    }

    /**
     * Zwraca informacje czy encja posiada pole idExternal
     * @return bool
     * @throws \ReflectionException
     */
    public function hasPropertyIdExternal(): bool
    {
        $reflectionClass = new ReflectionClass($this->repository->getEntityClass());

        if ($reflectionClass->hasProperty('idExternal')) {
            return true;
        }

        return false;
    }

    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param int|null $id
     * @param string|null $idExternal
     * @param bool $executeNotFoundException
     * @return int|null
     * @throws \ReflectionException
     */
    public function getIdIfExist(?int $id = null, ?string $idExternal = null, bool $executeNotFoundException = true): ?int
    {
        $entityId = null;

        if(!empty($id)){
            $entityId = current(
                $this->repository->findByQueryFiltersView(
                    queryFilters: [new QueryFilter('id', $id)],
                    fields: ['id']
                )
            );
        }

        if(empty($entityId) && $this->hasPropertyIdExternal() && !empty($idExternal)){
            $entityId = current(
                $this->repository->findByQueryFiltersView(
                    queryFilters: [new QueryFilter('idExternal', $idExternal)],
                    fields: ['id']
                )
            );
        }

        if($entityId === false || empty($entityId['id'])){
            if($executeNotFoundException){
                /** @var CommonLogicException $exception */
                $exception = new $this->notFoundException;
                throw $exception->setAdditionalMessageAdminApi(
                    $this->entityDomainServiceShareMethodsHelper->translator->trans('exceptions.details_filter', [
                        '%id%' => $id ?? 'null',
                        '%idExternal%' => $idExternal ?? 'null',
                    ])
                );

            }

            return null;
        }

        return $entityId['id'];
    }

    /**
     * Wyszukuje encje i ją zwraca, jeśli istnieje na podstawie przekazanych parametrów
     * @param int|null $id
     * @param string|null $idExternal
     * @param bool $executeNotFoundException
     * @return AbstractEntity|null
     * @throws \ReflectionException
     */
    public function findEntityForModify(?int $id = null, ?string $idExternal = null, bool $executeNotFoundException = true): ?AbstractEntity
    {
        $entity = null;

        //Szukamy po id wewnętrznym
        if(!empty($id)){
            $entity = $this->repository->findOneBy(['id' => $id]);
        }

        //Jeśli nie znaleźliśmy wewnętrznym, szukamy po zewnętrznym id, jeśli został wysłany
        if ($entity === null && $this->hasPropertyIdExternal() && $idExternal) {
            $entity = $this->repository->findOneBy(['idExternal' => $idExternal]);
        }

        if (!$entity instanceof AbstractEntity) {
            if($executeNotFoundException){
                throw new $this->notFoundException;
            }

            return null;
        }

        return $entity;
    }

    /**
     * Wyszukuje encje i ją zwraca, jeśli istnieje na podstawie id lub idExternal znajdujących się tablicy Data
     * @param array $data
     * @param bool $executeNotFoundException
     * @return AbstractEntity|null
     * @throws \ReflectionException
     */
    public function findEntityForModifyByData(array $data = [], bool $executeNotFoundException = true): ?AbstractEntity
    {
        $id = $data['id'] ?? null;
        $idExternal = $data['idExternal'] ?? null;

        return $this->findEntityForModify($id, $idExternal, $executeNotFoundException);
    }

    /**
     * Przygotowuje joiny dla zapytania
     * @param array|null $fieldsArray
     * @return array
     */
    public function prepareJoins(?array $fieldsArray): array
    {
        $fieldsWhichRequireJoin = QueryJoinsHelper::prepareFieldsWhichRequireJoinsByFieldNames($fieldsArray);

        $joins = [];

//        Przykład:
//        if (array_key_exists('userId', $fieldsWhichRequireJoin)) {
//            $joins[] = new QueryJoin(User::class, 'userId', ['userId' => 'userId.id']);
//        }

        return $joins;
    }
}
