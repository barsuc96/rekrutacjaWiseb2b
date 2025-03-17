<?php

namespace Wise\Core\Domain\Interfaces;

use Wise\Core\Entity\AbstractEntity;

interface EntityDomainServiceInterface
{
    public function getCurrentEntityName(): string;

    public function isExists(array $criteria): bool;
    public function hasPropertyIdExternal(): bool;
    public function getIdIfExist(?int $id = null, ?string $idExternal = null, bool $executeNotFoundException = true): ?int;
    public function findEntityForModify(?int $id = null, ?string $idExternal = null, bool $executeNotFoundException = true): ?AbstractEntity;
    public function findEntityForModifyByData(array $data = [], bool $executeNotFoundException = true): ?AbstractEntity;
    public function prepareJoins(?array $fieldsArray): array;
}
