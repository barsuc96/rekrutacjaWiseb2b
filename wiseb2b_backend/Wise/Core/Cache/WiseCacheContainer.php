<?php

declare(strict_types=1);

namespace Wise\Core\Cache;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CallbackInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Wise\Core\Cache\Interface\WiseCacheContainerInterface;

class WiseCacheContainer implements WiseCacheContainerInterface
{
    public function __construct(
        private readonly TagAwareCacheInterface $cacheWise
    ) {
    }

    public function delete(string $key): bool
    {
        return $this->cacheWise->delete($key);
    }

    public function get(
        string $key,
        callable|CallbackInterface $callback,
        float|null $beta = null,
        array &$metadata = null
    ): mixed {
        return $this->cacheWise->get($key, $callback, $beta, $metadata);
    }

    public function invalidateTags(array $tags): bool
    {
        return $this->cacheWise->invalidateTags($tags);
    }

    public function getItem($key): CacheItem
    {
        return $this->cacheWise->getItem($key);
    }

    public function save(CacheItemInterface $item): bool
    {
        return $this->cacheWise->save($item);
    }

    public function hasItem($key): bool
    {
        return $this->cacheWise->hasItem($key);
    }

    public function deleteItem($key): bool
    {
        return $this->cacheWise->deleteItem($key);
    }

}
