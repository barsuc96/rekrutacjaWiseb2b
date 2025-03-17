<?php

declare(strict_types=1);

namespace Wise\Core\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Wise\Core\Entity\AbstractEntity;

class RepositoryManager implements RepositoryManagerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function commit(): void
    {
        $this->entityManager->commit();
    }

    public function rollback(): void
    {
        $this->entityManager->rollback();
    }

    public function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    public function clear(): void
    {
        $this->entityManager->clear();
    }

    public function persist(AbstractEntity $entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function undoLastChanges(): void
    {
        $this->rollback();
        $this->beginTransaction();
    }
}
