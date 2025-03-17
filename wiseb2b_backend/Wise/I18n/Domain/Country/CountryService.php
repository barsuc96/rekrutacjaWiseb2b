<?php

declare(strict_types=1);

namespace Wise\I18n\Domain\Country;

use Wise\Core\Exception\ObjectNotFoundException;

class CountryService implements CountryServiceInterface
{
    public function __construct(
        private readonly CountryRepositoryInterface $countryRepository
    ) {}

    /**
     * @throws ObjectNotFoundException
     */
    public function getOrCreateCountry($countryId, $countryIdExternal): Country
    {
        $country = null;

        // Sprawdzamy czy istnieje powiązany product po jego id, a jeżeli nie podano to po jego idExternal
        if (!is_null($countryId)) {
            $country = $this->countryRepository->findOneBy(['id' => $countryId]);
        } elseif (!is_null($countryIdExternal)) {
            $country = $this->countryRepository->findOneBy(['idExternal' => $countryIdExternal]);
        }

        // Jeżeli produktu nie ma w bazie to dodajemy jego wpis, ale jako nieaktywny
        if (!($country instanceof Country)) {
            if (!is_null($countryIdExternal)) {
                $country = (new Country())->setIsActive(false)->setIdExternal($countryIdExternal);
                $country = $this->countryRepository->save($country);
            } else {
                throw new ObjectNotFoundException('Obiekt Country nie istnieje i niemożliwy do założenia');
            }
        }

        return $country;
    }

    /**
     * Metoda na podstawie wskazanych do wyciągnięcia pól ($fieldNames) przygotowuje joiny do zapytania
     */
    public function prepareJoins(?array $fieldsArray): array
    {
        return [];
    }
}
