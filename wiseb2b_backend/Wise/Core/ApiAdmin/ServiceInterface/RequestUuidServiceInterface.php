<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\ServiceInterface;

interface RequestUuidServiceInterface
{
    public function create(?string $uuid): void;

    public function getUuid(): ?string;
}