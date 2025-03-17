<?php

declare(strict_types=1);

namespace Wise\Security\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use League\Bundle\OAuth2ServerBundle\Entity\RefreshToken;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @extends ServiceEntityRepository<RefreshToken>
 *
 * @method RefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefreshToken[]    findAll()
 * @method RefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefreshTokenRepository extends EntityRepository
{
    /**
     * @throws NonUniqueResultException
     */
    public function findByAccessToken(string $accessToken)
    {
        $alias = 'refreshToken';
        $queryBuilder = $this->createQueryBuilder($alias);

        $queryBuilder
            ->where($alias.'.accessToken = :accessToken')
            ->setParameter('accessToken', $accessToken)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
