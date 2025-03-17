<?php

namespace Wise\Agreement\ApiUi\Service\PanelManagement;

use Symfony\Component\HttpFoundation\InputBag;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementContractsTypesSystemDictionaryServiceInterface;
use Wise\Agreement\Service\Agreement\Interfaces\CanUserAccessToAgreementServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;

class GetPanelManagementContractsTypesSystemDictionaryService extends AbstractGetListUiApiService implements GetPanelManagementContractsTypesSystemDictionaryServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly CanUserAccessToAgreementServiceInterface $canUserAccessToAgreementService
    ){
        parent::__construct($sharedActionService);
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
     * ## Logika obsługi metody GET LIST
     * @param InputBag $parameters
     * @return array
     * @throws \Exception
     */
    public function get(InputBag $parameters): array
    {
        $result = [
            [
                'type' => 'ContractContext',
                'description' => 'Określa kontekst prośby. Gdzie ma zostać wyświetlona prośba'
            ],
            [
                'type' => 'ContractImpact',
                'description' => 'Określa na kogo oddziałuje umowa'
            ],
            [
                'type' => 'ContractRequirement',
                'description' => 'Określa do czego jest wymagana umowa'
            ],
            [
                'type' => 'ContractType',
                'description' => 'Określa typy umów - główny element do edycji'
            ],
        ];

        $this->setTotalCount(count($result));

        return $result;
    }
}
