<?php

declare(strict_types=1);

namespace Wise\MultiStore\ApiUi\Service;

use Wise\Core\ApiUi\Dto\DictionaryResponseDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\MultiStore\ApiUi\Service\Interfaces\GetStoreDictionaryServiceInterface;
use Wise\MultiStore\Service\Store\Interfaces\ListStoreServiceInterface;

class GetStoreDictionaryService extends AbstractGetService implements GetStoreDictionaryServiceInterface
{
    protected const SERVICE_PARAMS_DTO = CommonListParams::class;
    protected const RESPONSE_DTO = DictionaryResponseDto::class;

    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListStoreServiceInterface $service,
    ) {
        parent::__construct($shareMethodsHelper, $service);
    }

    /**
     * Metoda pozwala na dodanie własnych filtrów do listy filtrów
     * Zwraca wartość bool wskazującą, czy dalsze przetwarzanie bieżącego pola powinno być kontynuowane.
     * Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     * @param array $filters Referencja do tablicy filtrów, do której można dodać własne filtry.
     * @param int|string $field Nazwa parametru do przetworzenia.
     * @param mixed $value Wartość parametru do przetworzenia.
     * @example Wise\Order\ApiUi\Service\Orders\GetOrdersService
     * @return bool Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     */
    protected function customInterpreterParameters(array &$filters, int|string $field, mixed $value): bool
    {
        if($field === 'value'){
            $filters[] = new QueryFilter('id', intval($value));

            return true;
        }

        return false;
    }

    /**
     * Metoda definiuje mapowanie pól z Response DTO, których nazwy NIE SĄ ZGODNE z domeną i wymagają mapowania.
     * @param array $fieldMapping
     * @return array
     */
    protected function prepareCustomFieldMapping(array $fieldMapping = []): array
    {
        return array_merge($fieldMapping, [
            'value' => 'id',
            'text' => 'name',
        ]);
    }
}
