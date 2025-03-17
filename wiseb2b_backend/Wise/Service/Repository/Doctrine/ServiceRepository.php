<?php

declare(strict_types=1);

namespace Wise\Service\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\Translations;
use Wise\Core\Repository\AbstractRepository;
use Wise\Service\Domain\Service\Service;
use Wise\Service\Domain\Service\ServiceRepositoryInterface;
use Wise\Service\Domain\ServiceTranslation\ServiceTranslation;
use Wise\Service\Domain\Service\ServiceTranslationRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Service>
 *
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends AbstractRepository implements ServiceRepositoryInterface
{
    protected const ENTITY_CLASS = Service::class;

    public function __construct(
        ManagerRegistry $registry,
        private readonly ServiceTranslationRepositoryInterface $translationRepository
    ) {
        parent::__construct($registry);
    }
    public function getTranslationClass(): string
    {
        return ServiceTranslation::class;
    }

    public function getTranslationEntityIdField(): string
    {
        return 'serviceId';
    }

    public function removeById(int $id, $flush = false): void
    {
        parent::removeById($id);

        //Usuwamy powiazane z tłumaczenia
        $this->translationRepository->removeByServiceId($id);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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
        $services = parent::findByQueryFiltersView(
            $queryFilters,
            $orderBy,
            $limit,
            $offset,
            $fields,
            $joins
        );

        /** @var Service $service */
        foreach ($services as & $service) {
            $serviceTranslations = $this->translationRepository->findByQueryFiltersView(
                [
                    new QueryFilter(
                        'serviceId', $service['t0_id'] ?? $service['id']
                    ),
                ]
            );

            $descriptions = [];
            $names = [];
            foreach ($serviceTranslations as $serviceTranslation) {
                $names[$serviceTranslation['language']] = [
                    'language' => $serviceTranslation['language'],
                    'translation' => $serviceTranslation['name'],
                ];

                $descriptions[$serviceTranslation['language']] = [
                    'language' => $serviceTranslation['language'],
                    'translation' => $serviceTranslation['description'],
                ];
            }
            $service['name'] = array_values($names);
            $service['description'] = array_values($descriptions);
        }

        return $services;
    }
}
