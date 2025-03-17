<?php

namespace Wise\Core\ApiAdmin\Service\Trait;

use Wise\Core\Api\Helper\PresentationServiceHelper;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonRemoveParams;

/**
 * # Podstawowa mechanika obsługująca metody DELETE w AdminApi
 */
trait CoreAdminApiDeleteMechanicTrait
{
    /**
     * Tablica mapująca nazwy pól z dto na nazwy pól w encji
     */
    protected ?array $fieldMapping = [];

    /**
     * Metoda służąca do obsługi metody DELETE
     * @param AbstractDto $deleteDto
     * @return array
     * @throws \ReflectionException
     */
    public function delete(AbstractDto $deleteDto): array
    {
        $this->updateProperties($deleteDto);

        // Przygotowanie parametrów dla serwisu usuwającego
        $serviceDTO = new CommonRemoveParams();

        // Przygotowanie filtrów (ponieważ AbstractRemoveService wykorzystuje AbstractListService do wyszukiwania rekordów do usunięcia)
        $filters = [];
        foreach (CommonDataTransformer::transformToArray($deleteDto, $this->fieldMapping) as $field => $value) {
            $filters[] = new QueryFilter($field, $value);
        }

        // Wypełnienie parametrów
        $serviceDTO
            ->setFilters($filters)
            ->setContinueAfterError(false);

        // Wywołanie serwisu usuwającego
        $serviceDTO = ($this->service)($serviceDTO);

        return $serviceDTO->read();
    }

    /**
     * Metoda aktualizuje pola serwisu na podstawie przekazanych informacji z requesta
     * @param AbstractDto $deleteDto
     * @return void
     * @throws \ReflectionException
     */
    protected function updateProperties(AbstractDto $deleteDto): void
    {
        $this->fieldMapping = PresentationServiceHelper::prepareFieldMappingByReflection($deleteDto::class);
    }
}
