<?php

declare(strict_types=1);


namespace Wise\Client\Service\Client;

use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Domain\Client\ClientServiceInterface;
use Wise\Client\Domain\ClientStatus\ClientStatusServiceInterface;
use Wise\Client\Service\Client\Interfaces\GetClientDetailsServiceInterface;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractDetailsService;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;

class GetClientDetailsService extends AbstractDetailsService implements GetClientDetailsServiceInterface
{
    protected const AGGREGATES = ['clientRepresentative'];

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
        private readonly ClientServiceInterface $clientService,
        private readonly ClientStatusServiceInterface $clientStatusService,
    ) {
        parent::__construct($repository, $additionalFieldsService);
    }

    /**
     * Zwraca listę joinów dołączonych do zapytania
     * @param CommonDetailsParams $params
     * @param QueryFilter[] $filters
     * @return array
     */
    protected function prepareJoins(CommonDetailsParams $params, array $filters): array
    {
        return $this->clientService->prepareJoins($params->getFields());
    }

    /**
     * Hardkodowe uzupełnienie symbolu statusu dla zamówień
     * @param array $entities
     * @param array $fields
     * @return array
     */
    protected function prepareStatusSymbol(array $entity, array $fields): array
    {
        $statusSymbols = $this->clientStatusService->getClientStatusByStatusNumber($entity['status']);
        $entity['status'] = [
            'symbol' => $statusSymbols?->getSymbol(),
            'id' => $statusSymbols?->getId()
        ];

        return $entity;
    }

    /**
     * Przygotowuje listę pól do zwrócenia z SQL
     * @param array $nonModelFields
     * @param CommonDetailsParams $params
     * @return array
     */
    protected function prepareFields(array $nonModelFields, CommonDetailsParams $params): array
    {
        $fields = parent::prepareFields($nonModelFields, $params);
        if(!in_array('status', array_values($fields))){
            $fields['status'] = 'status';
        }

        return $fields;
    }

    /**
     * Metoda wywoływana po znalezieniu elementu
     * @param array $entity
     * @return void
     * @throws FeatureNotImplemented
     */
    protected function afterFindEntity(array $entity): void
    {
        /** Wywołujemy metodę repository, aby odczytała i z'cache'owała , które później wykorzystujemy przy pobieraniu dodatkowych pól:
         *  offerCount, orderCount, firstName, lastName
         */
        $this->repository->getAdditionalDataByIds(array_column($entity, 'id'), true);
    }
}
