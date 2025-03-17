<?php

declare(strict_types=1);

namespace Wise\User\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Wise\Core\Repository\AbstractRepository;
use Wise\User\Domain\Agreement\AgreementTranslation;
use Wise\User\Domain\Agreement\AgreementTranslationRepositoryInterface;

/**
 * @extends ServiceEntityRepository<AgreementTranslation>
 *
 * @method AgreementTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgreementTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgreementTranslation[]    findAll()
 * @method AgreementTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgreementTranslationRepository extends AbstractRepository implements AgreementTranslationRepositoryInterface
{
    protected const ENTITY_CLASS = AgreementTranslation::class;

    public function removeByAgreementId(int $agreementId, bool $flush = false): void
    {
        $query = $this->getEntityManager()->createQuery(
            'DELETE FROM ' . self::ENTITY_CLASS . ' a WHERE a.agreementId = :agreementId'
        );
        $query
            ->setParameter('agreementId', $agreementId, Types::INTEGER)
            ->execute();

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }
}
