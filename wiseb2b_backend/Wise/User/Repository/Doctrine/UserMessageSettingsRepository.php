<?php

declare(strict_types=1);

namespace Wise\User\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Core\Repository\AbstractRepository;
use Wise\User\Domain\UserMessageSettings\UserMessageSettings;
use Wise\User\Domain\UserMessageSettings\UserMessageSettingsRepositoryInterface;

/**
 * @extends ServiceEntityRepository<UserMessageSettings>
 *
 * @method UserMessageSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMessageSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMessageSettings[]    findAll()
 * @method UserMessageSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMessageSettingsRepository extends AbstractRepository implements UserMessageSettingsRepositoryInterface
{
    protected const ENTITY_CLASS = UserMessageSettings::class;
}
