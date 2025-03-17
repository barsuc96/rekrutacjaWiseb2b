<?php

namespace Wise\Service\ApiUi\Service\PanelManagement;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\Service\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementServiceServiceInterface;
use Wise\Service\Service\Service\Interfaces\GetServiceDetailsServiceInterface;

class GetPanelManagementServiceService extends AbstractGetDetailsUiApiService implements GetPanelManagementServiceServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly GetServiceDetailsServiceInterface $getServiceDetailsService,
        private readonly TranslatorInterface $translator,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
        private readonly CurrentUserServiceInterface $currentUserService,
    ){
        parent::__construct($sharedActionService, $getServiceDetailsService);
    }

    /**
     * Metoda umożliwiająca wykonanie pewnej czynności przed obsługą filtrów
     * @param InputBag $parametersAdjusted
     * @return void
     */
    protected function beforeInterpretParameters(InputBag $parametersAdjusted): void
    {
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     * @throws ExceptionInterface
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        $this->fields['name.[].language'] = 'name.[].language';
        $this->fields['name.[].translation'] = 'name.[].translation';
        $this->fields['description.[].language'] = 'description.[].language';
        $this->fields['description.[].translation'] = 'description.[].translation';

        $elementData = [
            ...$elementData,
            'nameFormatted' => $this->translationService->getTranslationForField($elementData['name'], $this->localeService->getCurrentLanguage()),
            'descriptionFormatted' => $this->translationService->getTranslationForField($elementData['description'], $this->localeService->getCurrentLanguage()),
        ];
    }

    /**
     * Metoda pozwala uzupełnić responseDto pojedyńczego elementu o dodatkowe informacje
     * @param AbstractDto $responseDtoItem
     * @param array $cacheData
     * @param array|null $serviceDtoItem
     * @return void
     */
    protected function fillResponseDto(AbstractDto $responseDtoItem, array $cacheData, ?array $serviceDtoItem = null): void
    {
        $responseDtoItem->setName($serviceDtoItem['name']);
        $responseDtoItem->setDescription($serviceDtoItem['description']);
    }

}
