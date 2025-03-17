<?php

declare(strict_types=1);

namespace Wise\Core\Repository;

use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Wise\Core\Cache\Interface\WiseCacheContainerInterface;
use Wise\Core\Entity\AbstractCachedEntity;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Enum\CacheTtlEnum;
use Wise\Core\Exception\QueryFilterComparatorNotSupportedException;

abstract class RedisRepository implements CachedRepositoryInterface
{
    protected const ENTITY_CLASS = '';
    protected const CACHE_TAG = '';

    /**
     * UWAGA
     * nazwa zmiennej ($cacheWiseDefault) koresponduje z jednym z pool zadeklarowanych w
     * config/packages/cache.yaml (tutaj cache.wise.defaul)
     * dzięki czemu dostaniemy standardowe ustawienia z pliku i nie trzeba tego konfigurować wprost
     */
    public function __construct(
        private readonly WiseCacheContainerInterface $cacheContainer
    )
    {
    }

    public function save(AbstractCachedEntity $entity, bool $flush = false): AbstractCachedEntity
    {

        $cacheEntity = $this->cacheContainer->getItem($this->getCacheKey($entity));
        $cacheEntity->tag($entity->getTags());
        $cacheEntity->expiresAfter($this->getCacheTtl());
        $cacheEntity->set($entity);
        $this->cacheContainer->save($cacheEntity);

        return $entity;
    }

    protected function getCacheTtl(): int
    {
        return CacheTtlEnum::VERY_LONG->value;
    }

    public function remove(AbstractEntity $entity, bool $flush = false): void
    {
        $this->cacheContainer->deleteItem($this->getCacheKey($entity));
    }

    /**
     * @deprecated
     * @param int $id
     * @return void
     */
    public function removeById(int $id):void
    {
        throw new \BadFunctionCallException("dont use removeById on cache repository - use removeByCacheKey instead ");
    }

    /**
     * @throws QueryFilterComparatorNotSupportedException
     * @throws FeatureNotImplemented
     */
    public function findByQueryFilters(array $queryFilters, array $orderBy = null, $limit = null, $offset = null)
    {
        throw new FeatureNotImplemented();
    }

    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $cacheItem = $this->cacheContainer->getItem($this->getFullCacheKeyById($id));
        if (!$cacheItem) {
            return null;
        }
        return $cacheItem->get();
    }

    public function findAll() {
        throw new FeatureNotImplemented();
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->find($criteria['cacheHash']);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        throw new FeatureNotImplemented();
    }

    public function isExists(array $criteria): bool
    {
        return $this->cacheContainer->hasItem($this->getFullCacheKeyById($criteria['id']));
    }

    /**
     * Wykonuje zapytanie podobne do @type  AbstractRepository::findByQueryFiltersView $func
     * @param array $queryFilters
     * @param array|null $joins
     * @return int
     * @throws FeatureNotImplemented
     * @throws QueryFilterComparatorNotSupportedException
     */
    public function getTotalCountByQueryFilters(
        array $queryFilters,
        ?array $joins = [],
        string $countField = 'id'
    ): int {
        throw new FeatureNotImplemented();
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
        throw new FeatureNotImplemented();
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
        throw new FeatureNotImplemented();
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
        throw new FeatureNotImplemented();
    }

    public function aggregateByFilters(
        array $queryFilters, // np. [new QueryFilter('id', 1, QueryFilter::COMPARATOR_EQUAL)]
        array $fields
    ): ?array {
        throw new FeatureNotImplemented();
    }


    protected function getCacheKey(AbstractCachedEntity $entity):string {
        return $this->getFullCacheKeyById($entity->getCacheKey());
    }

    protected function getFullCacheKeyById(string $cacheHash) {
        return static::CACHE_TAG."-".$cacheHash;
    }

    public function invalidateTags(array $tags): void
    {
        $this->cacheContainer->invalidateTags($tags);
    }
}
