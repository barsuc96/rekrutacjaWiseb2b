<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Domain\Client\ClientServiceInterface;
use Wise\Client\Domain\ClientStatus\ClientStatusServiceInterface;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\CommonListParams;

class ListClientsService extends AbstractListService implements ListClientsServiceInterface
{
    protected const ENABLE_SEARCH_KEYWORD = true;

    /**
     * Pola obsługiwane ręcznie przez metody
     * Klucz to nazwa pola a wartość to nazwa metody obsługującej
     */
    protected const MANUALLY_HANDLED_FIELDS = [
        'status.symbol' => 'prepareStatusSymbol',
        'status.id' => 'prepareStatusSymbol',
    ];

    public function __construct(
        private readonly ClientRepositoryInterface $repository,
        private readonly ClientAdditionalFieldsService $additionalFieldsService,
        private readonly ClientStatusServiceInterface $clientStatusService,
        private readonly ClientServiceInterface $clientService
    ){
        parent::__construct($repository, $additionalFieldsService);
    }

    /**
     * Metoda wywoływana po znalezieniu elementów
     * @param array $entities
     * @return void
     */
    protected function afterFindEntities(array &$entities): void
    {
        /** Wywołujemy metodę repository, aby odczytała i z'cache'owała , które później wykorzystujemy przy pobieraniu dodatkowych pól:
         *  offerCount, orderCount, firstName, lastName
         */
        $this->repository->getAdditionalDataByIds(array_column($entities, 'id'), true);
    }

    /**
     * Lista pól, które mają być obsługiwane w filtrowaniu z pola searchKeyword
     * @return string[]
     */
    protected function getDefaultSearchFields(): array
    {
        return [
            'email', 'name', 'firstName', 'lastName', 'phone', 'taxNumber', 'id'
        ];
    }

    /**
     * Hardkodowe uzupełnienie symbolu statusu dla zamówień
     * @param array $entities
     * @param array $fields
     * @return array
     */
    protected function prepareStatusSymbol(array $entities, array $fields): array
    {
        foreach ($entities as &$entity){
            $statusSymbols = $this->clientStatusService->getClientStatusByStatusNumber($entity['status']);
            $entity['status'] = [
                'symbol' => $statusSymbols?->getSymbol(),
                'id' => $statusSymbols?->getId()
            ];
        }

        return $entities;
    }

    /**
     * Przygotowuje listę pól do zwrócenia z SQL
     * @param array $nonModelFields
     * @param CommonListParams $params
     * @return array
     */
    protected function prepareFields(array $nonModelFields, CommonListParams $params): array
    {
        $fields = parent::prepareFields($nonModelFields, $params);
        if(!in_array('status', array_values($fields))){
            $fields['status'] = 'status';
        }

        return $fields;
    }


    /**
     * Zwraca listę joinów dołączonych do zapytania
     * @param CommonListParams $params
     * @param QueryFilter[] $filters
     * @return array
     */
    protected function prepareJoins(CommonListParams $params, array $filters): array
    {
        return $this->clientService->prepareJoins($params->getFields());
    }
}
