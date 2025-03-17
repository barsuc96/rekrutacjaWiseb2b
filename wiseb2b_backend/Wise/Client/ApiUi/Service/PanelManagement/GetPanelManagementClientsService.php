<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\PanelManagement;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Client\ApiUi\Dto\PanelManagement\GetPanelManagementClientResponseDto;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementClientsServiceInterface;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Service\CommonListParams;

class GetPanelManagementClientsService extends AbstractGetService implements GetPanelManagementClientsServiceInterface
{
    /**
     * Klasa parametrów dla serwisu
     */
    protected const SERVICE_PARAMS_DTO = CommonListParams::class;

    /**
     * Klasa odpowiedzi dla zapytania GET
     */
    protected const RESPONSE_DTO = GetPanelManagementClientResponseDto::class;

    /**
     * Czy serwis ma zwracać ilość wszystkich rekordów
     */
    protected bool $fetchTotalCount = true;

    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListClientsServiceInterface $listClientsService,
        private readonly TranslatorInterface $translator
    )
    {
        parent::__construct($shareMethodsHelper, $listClientsService);
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        return array_merge(
            parent::prepareCustomFieldMapping($fieldMapping),
            [
                'clientGroup' => 'clientGroupId.name',
            ]
        );
    }
}
