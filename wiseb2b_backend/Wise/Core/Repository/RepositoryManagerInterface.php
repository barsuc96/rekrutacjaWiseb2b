<?php

declare(strict_types=1);

namespace Wise\Core\Repository;

use Wise\Core\Entity\AbstractEntity;

interface RepositoryManagerInterface
{
    public function flush(): void;

    public function commit(): void;

    public function rollback(): void;

    public function beginTransaction(): void;

    public function undoLastChanges(): void;

    public function clear(): void;

    public function persist(AbstractEntity $entity): void;
}