<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Country;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\I18n\Domain\Country\CountryRepositoryInterface;
use Wise\I18n\Service\Country\Interfaces\CountryHelperInterface;
use Wise\I18n\Service\Country\Interfaces\ListCountriesServiceInterface;

class CountryHelper implements CountryHelperInterface
{
    public function __construct(
        private readonly CountryRepositoryInterface $countryRepository,
        private readonly ListCountriesServiceInterface $listCountriesService,
        private readonly TranslationService $translationService
    ){}

    /**
     * Zwraca identyfikator kraju na podstawie kodu ISO
     * @param string $iso
     * @return int|null
     */
    public function getCountryIdByISO(string $iso): ?int
    {
        $country = $this->countryRepository->findOneBy(['idExternal' => strtoupper($iso)]);


        return $country?->getId();
    }

    /**
     * Zwraca nazwę kraju na podstawie kodu ISO
     * @param array $countryCodesArray
     * @param string $language
     * @return array
     * @throws ExceptionInterface
     */
    public function getCountryNamesByIso(array $countryCodesArray, string $language): array
    {
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('idExternal', $countryCodesArray, QueryFilter::COMPARATOR_IN)
            ])
            ->setFields([]);

        $countries = ($this->listCountriesService)($params)->read();

        $result = [];

        foreach ($countries as $country) {
            $result[$country['idExternal']] = $this->translationService->getTranslationForField($country['name'], $language);
        }

        return $result;
    }
}
