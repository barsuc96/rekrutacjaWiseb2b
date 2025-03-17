<?php

declare(strict_types=1);

namespace Wise\Core\Entity;

abstract class AbstractCachedEntity extends AbstractEntity
{
    /** klucz do zapisu cache. */
    protected ?string $cacheKey = null;

    /**
     * Tagi
     */
    protected array $tags = [];

	/**
	 * klucz do zapisu cache.
	 * @return
	 */
	public function getCacheKey(): ?string {
		return $this->cacheKey;
	}

	/**
	 * klucz do zapisu cache.
	 * @param  $cacheKey klucz do zapisu cache.
	 * @return self
	 */
	public function setCacheKey(?string $cacheKey): self {
		$this->cacheKey = $cacheKey;
		return $this;
	}

    /**
     * Zwraca tagi
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Zapisuje tagi
     * @param array $tags
     * @return $this
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }
}
