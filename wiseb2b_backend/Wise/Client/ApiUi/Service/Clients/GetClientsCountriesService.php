<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\Clients;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Client\ApiUi\Service\Clients\Interfaces\GetClientsCountriesServiceInterface;
use Wise\Client\Service\Client\Interfaces\ListClientsCountriesServiceInterface;
use Wise\Client\Service\Client\ListClientsCountriesParams;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;

/**
 * Serwis api - dla pobrania listy krajów przy edycji karty klienta (słownik krajów)
 */
class GetClientsCountriesService extends AbstractGetListUiApiService implements GetClientsCountriesServiceInterface
{
    /**
     * Klasa parametrów dla serwisu
     */
    protected string $serviceParamsDto = ListClientsCountriesParams::class;

    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListClientsCountriesServiceInterface $service,
        private readonly LocaleServiceInterface $localeService,
        private readonly TranslationService $translationService
    ) {
        parent::__construct($shareMethodsHelper, $service);
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     * @throws ExceptionInterface
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        $elementData['name'] = $this->translationService->getTranslationForField($elementData['name'], $this->localeService->getCurrentLanguage());
    }
}
