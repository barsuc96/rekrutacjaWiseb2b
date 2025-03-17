<?php

declare(strict_types=1);

namespace Wise\Core\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Core\Domain\SessionParam;
use Wise\Core\Repository\AbstractRepository;

/**
 * @extends ServiceEntityRepository<SessionParam>
 *
 * @method SessionParam|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionParam|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionParam[]    findAll()
 * @method SessionParam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionParamRepository extends AbstractRepository implements SessionParamRepositoryInterface
{
    protected const ENTITY_CLASS = SessionParam::class;
}