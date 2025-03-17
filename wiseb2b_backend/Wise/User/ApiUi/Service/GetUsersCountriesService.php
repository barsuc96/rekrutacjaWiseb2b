<?php

namespace Wise\User\ApiUi\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiverCountriesServiceInterface;
use Wise\User\ApiUi\Dto\Users\UsersCountryDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersCountriesServiceInterface;

class GetUsersCountriesService extends AbstractGetService implements GetUsersCountriesServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListReceiverCountriesServiceInterface $service,
        private readonly LocaleServiceInterface $localeService,
        private readonly TranslationService $translationService
    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [
            new QueryFilter('limit', null)
        ];

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'contentLanguage') {
                continue;
            }

            $filters[] = new QueryFilter($field, $value);
        }

        $fields = [
            'code' => 'idExternal',
        ];

        $fields = (new UsersCountryDto())->mergeWithMappedFields($fields);

        $params = new CommonListParams();

        $params
            ->setFilters($filters)
            ->setFields($fields);

        $serviceDtoData = ($this->service)($params)->read();

        $this->fillName($serviceDtoData);

        usort($serviceDtoData, function($a, $b) {
            if ($a['name'] == 'Polska') {
                return -1; // 'Polska' powinno być przed każdym innym elementem
            }
            if ($b['name'] == 'Polska') {
                return 1; // Każdy inny element powinien być za 'Polską'
            }
            return strcmp($a['name'], $b['name']); // Standardowe porównanie dla innych elementów
        });

        return (new UsersCountryDto())->fillArrayWithObjectMappedFields($serviceDtoData, $fields);
    }


    private function fillName(array &$serviceDtoData): void
    {
        foreach ($serviceDtoData as &$country) {
            $country['name'] = $this->translationService->getTranslationForField($country['name'], $this->localeService->getCurrentLanguage());
        }
    }
}
