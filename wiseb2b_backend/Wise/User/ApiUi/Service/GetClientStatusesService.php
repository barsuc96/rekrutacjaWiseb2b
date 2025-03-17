<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Client\Service\Client\Interfaces\ListClientStatusServiceInterface;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\User\ApiUi\Service\Interfaces\GetClientStatusesServiceInterface;

/**
 * Zwraca listę dostępnych statusów (klienta)
 */
class GetClientStatusesService extends AbstractGetListUiApiService implements GetClientStatusesServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListClientStatusServiceInterface $service,
        private readonly TranslatorInterface $translator
    ) {
        parent::__construct($shareMethodsHelper, $service);
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        $fieldMapping = parent::prepareCustomFieldMapping($fieldMapping);

        return array_merge($fieldMapping, [
            'status' => 'id',
            'statusSymbol' => 'symbol',
            'statusFormatted' => FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE
        ]);
    }

    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param CommonListParams|CommonDetailsParams $params
     * @return void
     */
    protected function fillParams(CommonListParams|CommonDetailsParams $params): void
    {
        parent::fillParams($params);
        $params->setFilters([]);
        $params->setFetchTotalCount(false);
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        $elementData['statusFormatted'] = null;

        if(!empty($elementData['symbol'])){
            $elementData['statusFormatted'] = $this->translator->trans('client.status.' . $elementData['symbol']);
        }
    }

}
