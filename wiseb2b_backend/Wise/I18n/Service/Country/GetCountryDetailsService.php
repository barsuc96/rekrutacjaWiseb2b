<?php

namespace Wise\I18n\Service\Country;

use Symfony\Component\Security\Core\Security;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\Object\ObjectNonModelFieldsHelper;
use Wise\Core\Model\QueryFilter;
use Wise\I18n\Domain\Country\Country;
use Wise\I18n\Domain\Country\CountryRepositoryInterface;
use Wise\I18n\Domain\Country\CountryServiceInterface;
use Wise\I18n\Service\Country\Interfaces\GetCountryDetailsServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;

class GetCountryDetailsService implements GetCountryDetailsServiceInterface
{
    public function __construct(
        private readonly CountryServiceInterface $countryService,
        private readonly CountryRepositoryInterface $countryRepository,
        private readonly CurrentUserServiceInterface $currentUserService
    )
    {
    }

    public function __invoke(GetCountryDetailsParams $params): CommonServiceDTO
    {
        $filters = $this->prepareFilters($params);

        $joins = $this->countryService->prepareJoins($params->getFields());
        $nonModelFields = ObjectNonModelFieldsHelper::find(Country::class, $params->getFields());

        /**
         * Metoda getByIdView zwraca array z danymi użytkownika
         */
        $countryData = $this->countryRepository->getByIdView(
            filters: $filters,
            fields: array_diff($params->getFields(), $nonModelFields),
            joins: $joins
        );

        ($resultDTO = new CommonServiceDTO())->writeAssociativeArray($countryData);

        return $resultDTO;
    }

    /**
     * Metoda przygotowująca filtry na podstawie podanych parametrów
     */
    protected function prepareFilters(GetCountryDetailsParams $params): array
    {
        if($params->getCountryId() !== null){
            $filters[] = new QueryFilter('id', $params->getCountryId());
        }

        if($params->getCountryIdExternal() !== null){
            $filters[] = new QueryFilter('idExternal', $params->getCountryIdExternal());
        }

        return $filters;
    }

    /**
     * Metoda sprawdza czy $userId istnieje, jeśli nie to pobieramy go z zalogowanego użytkownika
     */
    protected function getValidatedUserId(?int $userId = null): ?int
    {
        if ($userId === null) {
            $userId = $this->currentUserService->getUserId();
        }

        return $userId;
    }
}