<?php

declare(strict_types=1);

namespace Wise\User\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Core\Repository\AbstractRepository;
use Wise\User\Domain\UserMessageSettings\MessageSettings;
use Wise\User\Domain\UserMessageSettings\MessageSettingsRepositoryInterface;

/**
 * @extends ServiceEntityRepository<MessageSettings>
 *
 * @method MessageSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageSettings[]    findAll()
 * @method MessageSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageSettingsRepository extends AbstractRepository implements MessageSettingsRepositoryInterface
{
    protected const ENTITY_CLASS = MessageSettings::class;
}
