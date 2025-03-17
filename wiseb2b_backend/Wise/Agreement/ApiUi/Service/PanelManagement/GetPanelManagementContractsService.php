<?php

namespace Wise\Agreement\ApiUi\Service\PanelManagement;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementContractsServiceInterface;
use Wise\Agreement\Service\Agreement\Interfaces\CanUserAccessToAgreementServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;

class GetPanelManagementContractsService extends AbstractGetListUiApiService implements GetPanelManagementContractsServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListContractServiceInterface $listContractService,
        private readonly TranslatorInterface $translator,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly CanUserAccessToAgreementServiceInterface $canUserAccessToAgreementService,
    ){
        parent::__construct($sharedActionService, $listContractService);
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
        if(empty($elementData)){
            return;
        }

        $elementData = [
            ...$elementData,
            'nameFormatted' => empty($elementData['name']) ? null : $this->translationService->getTranslationForField($elementData['name'], $this->localeService->getCurrentLanguage()),
            'contentFormatted' => empty($elementData['content']) ? null :  $this->translationService->getTranslationForField($elementData['content'], $this->localeService->getCurrentLanguage()),
        ];
    }
}
