<?php

declare(strict_types=1);

namespace Wise\I18n\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\Translations;
use Wise\Core\Repository\AbstractRepository;
use Wise\I18n\Domain\Country\Country;
use Wise\I18n\Domain\Country\CountryRepositoryInterface;
use Wise\I18n\Domain\Country\CountryTranslation;
use Wise\I18n\Domain\Country\CountryTranslationRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Country>
 *
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends AbstractRepository implements CountryRepositoryInterface
{
    protected const ENTITY_CLASS = Country::class;

    public function __construct(
        ManagerRegistry $registry,
        private readonly CountryTranslationRepositoryInterface $translationRepository
    ) {
        parent::__construct($registry);
    }

    public function removeById(int $id, $flush = false): void
    {
        parent::removeById($id);

        //Usuwamy powiazane tłumaczenia
        $this->translationRepository->removeByCountryId($id);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function save(AbstractEntity $entity, bool $flush = false): AbstractEntity
    {
        $this->getEntityManager()->persist($entity);
        $this->translationRepository->removeByCountryId($entity->getId());
        $countryTranslations = [];

        $name = $entity->getName();
        if ($name instanceof Translations) {
            foreach ($name?->getTranslations() as $name) {
                if (isset($countryTranslations[$name->getLanguage()])) {
                    $countryTranslations[$name->getLanguage()]->setName($name->getTranslation());
                } else {
                    $countryTranslations[$name->getLanguage()] = (new CountryTranslation())
                        ->setCountryId($entity->getId())
                        ->setLanguage($name->getLanguage())
                        ->setName($name->getTranslation());
                }
            }
        }

        foreach ($countryTranslations as $countryTranslation) {
            $this->translationRepository->save($countryTranslation);
        }

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
        $countries = parent::findByQueryFiltersView(
            $queryFilters,
            $orderBy,
            $limit,
            $offset,
            $fields,
            $joins
        );

        /** @var Country $country */
        foreach ($countries as & $country) {
            $countryTranslations = $this->translationRepository->findByQueryFiltersView(
                [new QueryFilter('countryId', $country['t0_id'] ?? $country['id'])]
            );

            $names = [];
            foreach ($countryTranslations as $countryTranslation) {
                $names[$countryTranslation['language']] = [
                    'language' => $countryTranslation['language'],
                    'translation' => $countryTranslation['name'],
                ];
            }
            $country['name'] = array_values($names);
        }

        return $countries;
    }
}
