<?php

declare(strict_types=1);

namespace Wise\I18n\Repository\Doctrine;

use Doctrine\DBAL\Types\Types;
use Wise\Core\Model\QueryParameters;
use Wise\Core\Repository\AbstractRepository;
use Wise\I18n\Domain\Country\CountryTranslation;
use Wise\I18n\Domain\Country\CountryTranslationRepositoryInterface;

class CountryTranslationRepository extends AbstractRepository implements CountryTranslationRepositoryInterface
{
    protected const ENTITY_CLASS = CountryTranslation::class;

    public function removeByCountryId(int $countryId, bool $flush = false): void
    {
        $query = $this->getEntityManager()->createQuery(
            'DELETE FROM ' . self::ENTITY_CLASS . ' ct WHERE ct.countryId = :countryId'
        );

        $query
            ->setParameter('countryId', $countryId, Types::INTEGER)
            ->execute();

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }
}
