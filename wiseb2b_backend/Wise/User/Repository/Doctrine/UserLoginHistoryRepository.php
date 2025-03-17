<?php

declare(strict_types=1);

namespace Wise\User\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Core\Repository\AbstractRepository;
use Wise\User\Domain\UserLoginHistory\UserLoginHistory;
use Wise\User\Domain\UserLoginHistory\UserLoginHistoryRepositoryInterface;

/**
 * @extends ServiceEntityRepository<UserLoginHistory>
 *
 * @method UserLoginHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLoginHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLoginHistory[]    findAll()
 * @method UserLoginHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLoginHistoryRepository extends AbstractRepository implements UserLoginHistoryRepositoryInterface
{
    protected const ENTITY_CLASS = UserLoginHistory::class;
}
