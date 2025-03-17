<?php

declare(strict_types=1);

namespace Wise\Core\Domain\Event;

abstract class EntityAfterRemoveEvent
{
    protected ?array $data;
    protected ?int $id;

    public function __construct(?int $id = null, ?array $data = null)
    {
        $this->id = $id;
        $this->data = $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?array
    {
        return $this->data;
    }
}
