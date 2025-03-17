<?php

declare(strict_types=1);

namespace Wise\User\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\Translations;
use Wise\Core\Repository\AbstractRepository;
use Wise\User\Domain\Agreement\Agreement;
use Wise\User\Domain\Agreement\AgreementRepositoryInterface;
use Wise\User\Domain\Agreement\AgreementTranslation;
use Wise\User\Domain\Agreement\AgreementTranslationRepositoryInterface;


/**
 * @extends ServiceEntityRepository<Agreement>
 *
 * @method Agreement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agreement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agreement[]    findAll()
 * @method Agreement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgreementRepository extends AbstractRepository implements AgreementRepositoryInterface
{
    protected const ENTITY_CLASS = Agreement::class;

    public function __construct(
        ManagerRegistry $registry,
        private readonly AgreementTranslationRepositoryInterface $translationRepository
    )
    {
        parent::__construct($registry);
    }

    public function removeById(int $id, $flush = false): void
    {
        parent::removeById($id);

        //Usuwamy powiazane z Agreement Translation
        $this->translationRepository->removeByAgreementId($id);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function save(AbstractEntity $entity, bool $flush = false): AbstractEntity
    {
        $this->getEntityManager()->persist($entity);
        $this->saveTranslations($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function findByQueryFiltersView(
        array $queryFilters,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        ?array $fields = [],
        ?array $joins = [],
        ?array $aggregates = [],
    ): array {
        $aggrements = parent::findByQueryFiltersView(
            $queryFilters,
            $orderBy,
            $limit,
            $offset,
            $fields,
            $joins
        );

        /** @var Agreement $agreement */
        foreach ($aggrements as & $agreement) {
            $agreementTranslations = $this->translationRepository->findByQueryFiltersView([
                new QueryFilter(
                    'agreementId', $agreement['t0_id'] ?? $agreement['id']
                ),
            ]);

            $contents = [];
            $names = [];
            foreach ($agreementTranslations as $agreementTranslation) {
                $names[$agreementTranslation['language']] = [
                    'language' => $agreementTranslation['language'],
                    'translation' => $agreementTranslation['name'],
                ];

                $contents[$agreementTranslation['language']] = [
                    'language' => $agreementTranslation['language'],
                    'translation' => $agreementTranslation['content'],
                ];
            }
            $agreement['name'] = array_values($names);
            $agreement['description'] = array_values($contents);
        }

        return $aggrements;
    }

    private function saveTranslations(AbstractEntity $entity): void
    {
        $this->translationRepository->removeByAgreementId($entity->getId());
        $agreementTranslations = [];

        /** @var Translations $contentTranslation */
        $contentTranslation = $entity->getContent();
        if ($contentTranslation instanceof Translations) {
            foreach ($contentTranslation?->getTranslations() as $content) {
                if (isset($agreementTranslations[$content->getLanguage()])) {
                    $agreementTranslations[$content->getLanguage()]->setContent(
                        $content->getTranslation()
                    );
                } else {
                    $agreementTranslations[$content->getLanguage()]
                        = (new AgreementTranslation())
                        ->setAgreementId($entity->getId())
                        ->setLanguage($content->getLanguage())
                        ->setContent($content->getTranslation());
                }
            }
        }

        /** @var Translations $nameTranslation */
        $nameTranslation = $entity->getName();
        if ($nameTranslation instanceof Translations) {
            foreach ($nameTranslation?->getTranslations() as $name) {
                if (isset($agreementTranslations[$name->getLanguage()])) {
                    $agreementTranslations[$name->getLanguage()]->setName(
                        $name->getTranslation()
                    );
                } else {
                    $agreementTranslations[$name->getLanguage()]
                        = (new AgreementTranslation())
                        ->setAgreementId($entity->getId())
                        ->setLanguage($name->getLanguage())
                        ->setName($name->getTranslation());
                }
            }
        }

        foreach ($agreementTranslations as $agreementTranslation) {
            $this->translationRepository->save($agreementTranslation);
        }
    }
}
