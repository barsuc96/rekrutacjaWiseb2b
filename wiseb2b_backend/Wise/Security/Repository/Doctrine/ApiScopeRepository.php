<?php

declare(strict_types=1);

namespace Wise\Security\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Wise\Security\Domain\Oauth2\ApiScope;

/**
 * @extends ServiceEntityRepository<ApiScope>
 *
 * @method ApiScope|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiScope|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiScope[]    findAll()
 * @method ApiScope[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiScopeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiScope::class);
    }

    public function add(ApiScope $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ApiScope $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
