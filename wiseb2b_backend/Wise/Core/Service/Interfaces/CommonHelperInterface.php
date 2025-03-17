<?php

namespace Wise\Core\Service\Interfaces;

interface CommonHelperInterface
{
    public function getIdIfExist(?int $id = null, ?string $idExternal = null, bool $executeNotFoundException = true): ?int;
    public function getIdIfExistByData(array $data, bool $executeNotFoundException = true): ?int;
    public function getIdIfExistByDataExternal(array $data, bool $executeNotFoundException = true): ?int;
    public function getIdExternal(int $id, bool $executeNotFoundException = true): ?string;
    public function prepareExternalData(array &$data, bool $executeNotFoundException = true): void;
}
