<?php

namespace Wise\User\Service\CountryCodes;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonListParams;
use Wise\User\Domain\CountryCode\CountryCode;
use Wise\User\Domain\CountryCode\CountryCodeRepositoryInterface;
use Wise\User\Service\CountryCodes\Interfaces\ListCountryCodesServiceInterface;
use Wise\User\WiseUserExtension;

/**
 * Serwis zwraca listę kodów krajów
 */
class ListCountryCodesService implements ListCountryCodesServiceInterface
{
    const COUNTRY_CODES_JSON_FILE = '/Wise/User/Resources/json/countryCodes.json';

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly ContainerBagInterface $configParams,
        private readonly TranslatorInterface $translator,
        private readonly CountryCodeRepositoryInterface $countryCodeRepository
    ){}

    public function __invoke(CommonListParams $params): CommonServiceDTO
    {
        $countryCodes = $this->getCountryCodes();
        $preparedCountryCodes = $this->prepareCountryCodes($countryCodes);

        $result = new CommonServiceDTO();
        $result->writeAssociativeArray($preparedCountryCodes);

        return $result;
    }

    protected function getCountryCodes(): array
    {
        return $this->countryCodeRepository->findByQueryFilters([]);
    }

    protected function prepareCountryCodes(array $countryCodes): array
    {
        $preparedCountryCodes = [];

        /** @var CountryCode $countryCode */
        foreach ($countryCodes as $countryCode) {
            if(!empty($this->availableCountries()) && !in_array(strtoupper($countryCode->getCode()), $this->availableCountries())) {
                continue;
            }

            $languageTranslation = $this->translator->trans('countries.' . $countryCode->getCode());

            $preparedCountryCodes[] = [
                'code' => $countryCode->getCode(),
                'language' => (str_contains($languageTranslation, 'countries')) ? $countryCode->getLanguage(): $languageTranslation
            ];
        }

        return $preparedCountryCodes;
    }

    protected function availableCountries(): array
    {
        return $this->configParams->get(WiseUserExtension::getExtensionAlias())['country_codes_service']['available_countries'] ?? [];
    }
}