<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Country;

use Exception;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Helper\Object\ObjectNonModelFieldsHelper;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\Core\Service\CommonListParams;
use Wise\I18n\Domain\Country\Country;
use Wise\I18n\Domain\Country\CountryRepositoryInterface;
use Wise\I18n\Domain\Country\CountryServiceInterface;
use Wise\I18n\Service\Country\Interfaces\ListCountriesServiceInterface;

/**
 * Serwis do pobrania listy krajów
 */
class ListCountriesService implements ListCountriesServiceInterface
{
    public function __construct(
        private readonly CountryRepositoryInterface $repository,
        private readonly CountryServiceInterface $countryService,
    ) {
    }

    /**
     * @param CommonListParams $params
     * @return CommonServiceDTO
     * @throws Exception
     */
    public function __invoke(CommonListParams $params): CommonServiceDTO
    {
        $joins = $this->countryService->prepareJoins($params->getFields());
        $nonModelFields = ObjectNonModelFieldsHelper::find(Country::class, $params->getFields());

        $queryParameters = QueryParametersHelper::prepareStandardParameters($params->getFilters());

        $queryParameters->setSortField('sortOrder');

        $entities = $this->repository->findByQueryFiltersView(
            queryFilters: $queryParameters->getQueryFilters(),
            orderBy: ['field' => $queryParameters->getSortField(), 'direction' => $queryParameters->getSortDirection()],
            limit: $queryParameters->getLimit(),
            offset: $queryParameters->getOffset(),
            fields: ArrayHelper::removeFieldsInArray($nonModelFields, $params->getFields()),
            joins: $joins
        );

        ($resultDTO = new CommonServiceDTO())->writeAssociativeArray($entities);

        return $resultDTO;
    }
}
