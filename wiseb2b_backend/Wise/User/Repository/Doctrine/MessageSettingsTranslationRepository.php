<?php

declare(strict_types=1);

namespace Wise\User\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Core\Repository\AbstractRepository;
use Wise\User\Domain\UserMessageSettings\MessageSettingsTranslation;
use Wise\User\Domain\UserMessageSettings\MessageSettingsTranslationRepositoryInterface;

/**
 * @extends ServiceEntityRepository<MessageSettingsTranslation>
 *
 * @method MessageSettingsTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageSettingsTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageSettingsTranslation[]    findAll()
 * @method MessageSettingsTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageSettingsTranslationRepository extends AbstractRepository implements
    MessageSettingsTranslationRepositoryInterface
{
    protected const ENTITY_CLASS = MessageSettingsTranslation::class;
}
