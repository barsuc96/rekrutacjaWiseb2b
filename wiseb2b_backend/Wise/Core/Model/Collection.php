<?php

declare(strict_types=1);

namespace Wise\Core\Model;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Iterator;
use IteratorAggregate;

/** @template T */
class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    /** @param array<array-key, T> $items */
    public function __construct(
        protected array $items = []
    ) {}

    /** @param T $item */
    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    /** @param T $item */
    public function contains(mixed $item): bool
    {
        foreach ($this->items as $i) {
            if ($i == $item) {
                return true;
            }
        }

        return false;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->items);
    }

    /** @return array<array-key, T> */
    public function __toArray(): array
    {
        return $this->items;
    }
}
