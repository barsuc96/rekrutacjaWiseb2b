<?php

namespace Wise\Receiver\ApiUi\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Receiver\ApiUi\Dto\ReceiverCountryDto;
use Wise\Receiver\ApiUi\Service\Interfaces\GetReceiversCountriesServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiverCountriesServiceInterface;

/**
 * Serwis api - dla pobrania listy krajów przy edycji adresu dostawy (słownik krajów)
 */
class GetReceiversCountriesService extends AbstractGetService implements GetReceiversCountriesServiceInterface
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
        $fields = [
            'code' => 'idExternal',
        ];

        $fields = (new ReceiverCountryDto())->mergeWithMappedFields($fields);

        $params = new CommonListParams();

        $params
            ->setFilters([
                new QueryFilter('limit', null)
            ])
            ->setFields($fields);

        $serviceDtoData = ($this->service)($params)->read();

        $this->fillName($serviceDtoData);

        return (new ReceiverCountryDto())->fillArrayWithObjectMappedFields($serviceDtoData, $fields);
    }


    private function fillName(array &$serviceDtoData): void
    {
        $showedLanguage = $this->localeService->getCurrentLanguage();
        if(strtoupper($showedLanguage) !== 'PL'){
            $showedLanguage = 'EN';
        }

        foreach ($serviceDtoData as &$country) {
            $country['name'] = $this->translationService->getTranslationForField($country['name'], $showedLanguage);
        }
    }
}
