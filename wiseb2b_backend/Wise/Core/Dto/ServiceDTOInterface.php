<?php

declare(strict_types=1);

namespace Wise\Core\Dto;

interface ServiceDTOInterface
{
    public function write(mixed $data, ?array $fieldMapping = []);
    public function mergeWithAssociativeArray(array $data): void;
    public function read(): ?array;
}
