<?php

declare(strict_types=1);

namespace Wise\User\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Core\Repository\AbstractRepository;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;


/**
 * @extends ServiceEntityRepository<UserAgreement>
 *
 * @method UserAgreement|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAgreement|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAgreement[]    findAll()
 * @method UserAgreement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAgreementRepository extends AbstractRepository implements UserAgreementRepositoryInterface
{
    protected const ENTITY_CLASS = UserAgreement::class;
}
