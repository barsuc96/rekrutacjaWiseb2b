<?php

declare(strict_types=1);

namespace Wise\User\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Core\Repository\AbstractRepository;
use Wise\User\Domain\UserRelation\UserRelation;
use Wise\User\Domain\UserRelation\UserRelationRepositoryInterface;

/**
 * @extends ServiceEntityRepository<UserRelation>
 *
 * @method UserRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRelation[]    findAll()
 * @method UserRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRelationRepository extends AbstractRepository implements UserRelationRepositoryInterface
{
    protected const ENTITY_CLASS = UserRelation::class;
}
