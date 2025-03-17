<?php

namespace Wise\Service\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Wise\Core\Repository\AbstractRepository;
use Wise\Service\Domain\ServiceTranslation\ServiceTranslation;
use Wise\Service\Domain\Service\ServiceTranslationRepositoryInterface;

/**
 * @extends ServiceEntityRepository<ServiceTranslation>
 *
 * @method ServiceTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceTranslation[]    findAll()
 * @method ServiceTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceTranslationRepository extends AbstractRepository implements ServiceTranslationRepositoryInterface
{
    protected const ENTITY_CLASS = ServiceTranslation::class;

    public function removeByServiceId(int $serviceId, bool $flush = false): void
    {
        $query = $this->getEntityManager()->createQuery(
            'DELETE FROM ' . self::ENTITY_CLASS . ' s WHERE s.serviceId = :serviceId'
        );

        $query
            ->setParameter('serviceId', $serviceId, Types::INTEGER)
            ->execute();

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }
}
