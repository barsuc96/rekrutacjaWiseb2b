<?php

declare(strict_types=1);

namespace Wise\Core\Cache\Interface;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CallbackInterface;

interface WiseCacheContainerInterface
{
    public function delete(string $key): bool;

    public function get(
        string $key,
        callable|CallbackInterface $callback,
        float|null $beta = null,
        array &$metadata = null): mixed;

    public function invalidateTags(array $tags): bool;

    public function getItem($key): CacheItem;

    public function save(CacheItemInterface $item): bool;

    public function hasItem($key): bool;

    public function deleteItem($key): bool;
}
