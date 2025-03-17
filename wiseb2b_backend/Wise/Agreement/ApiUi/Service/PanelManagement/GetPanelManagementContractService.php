<?php

namespace Wise\Agreement\ApiUi\Service\PanelManagement;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementContractServiceInterface;
use Wise\Agreement\Service\Agreement\Interfaces\CanUserAccessToAgreementServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\GetContractDetailsServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;

class GetPanelManagementContractService extends AbstractGetDetailsUiApiService implements GetPanelManagementContractServiceInterface
{

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly GetContractDetailsServiceInterface $getContractDetailsService,
        private readonly TranslatorInterface $translator,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly CanUserAccessToAgreementServiceInterface $canUserAccessToAgreementService
    ){
        parent::__construct($sharedActionService, $getContractDetailsService);
    }

    /**
     * Metoda umożliwiająca wykonanie pewnej czynności przed obsługą filtrów
     * @param InputBag $parametersAdjusted
     * @return void
     */
    protected function beforeInterpretParameters(InputBag $parametersAdjusted): void
    {
        $this->canUserAccessToAgreementService->check();
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     * @throws ExceptionInterface
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        $elementData = [
            ...$elementData,
            'nameFormatted' => $this->translationService->getTranslationForField($elementData['name'], $this->localeService->getCurrentLanguage()),
            'contentFormatted' => $this->translationService->getTranslationForField($elementData['content'], $this->localeService->getCurrentLanguage()),
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
        $responseDtoItem->setContent($serviceDtoItem['content']);
        $responseDtoItem->setTestimony($serviceDtoItem['testimony']);
    }
}
