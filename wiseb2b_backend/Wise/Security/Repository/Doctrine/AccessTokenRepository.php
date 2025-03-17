<?php

declare(strict_types=1);

namespace Wise\Security\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use League\Bundle\OAuth2ServerBundle\Entity\AccessToken;
use League\Bundle\OAuth2ServerBundle\Entity\RefreshToken;
use Wise\Core\Repository\AbstractRepository;

/**
 * @extends ServiceEntityRepository<AccessToken>
 *
 * @method AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessToken[]    findAll()
 * @method AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessTokenRepository extends AbstractRepository
{
    protected const ENTITY_CLASS = AccessToken::class;
    public function findByUserIdentifier(string $userIdentifier)
    {
        $alias = 'accessToken';
        $queryBuilder = $this->createQueryBuilder($alias);

        $queryBuilder
            ->where($alias.'.userIdentifier = :userIdentifier')
            ->andWhere($alias.'.revoked = :revoked')
            ->setParameter('userIdentifier', $userIdentifier)
            ->setParameter('revoked', false)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
