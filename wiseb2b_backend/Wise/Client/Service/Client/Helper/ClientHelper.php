<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\Helper;

use Wise\Client\Domain\Client\ClientServiceInterface;
use Wise\Client\Domain\ClientStatus\ClientStatusServiceInterface;
use Wise\Client\Service\Client\Helper\Interfaces\ClientHelperInterface;
use Wise\Core\DataTransformer\CommonDomainDataTransformer;
use Wise\Core\Service\AbstractHelper;

class ClientHelper extends AbstractHelper implements ClientHelperInterface
{
    public function __construct(
        private readonly ClientServiceInterface $clientService,
        private readonly ClientStatusServiceInterface $clientStatusService
    ){
        parent::__construct(
            entityDomainService: $clientService
        );
    }

    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param array $data
     * @param bool $executeNotFoundException
     * @return int|null
     * @throws \ReflectionException
     */
    public function getIdIfExistByDataExternal(array $data, bool $executeNotFoundException = true): ?int
    {
        $id = $data['clientId'] ?? null;
        $idExternal = $data['clientIdExternal'] ?? $data['clientExternalId'] ?? null;

        return $this->clientService->getIdIfExist($id, $idExternal, $executeNotFoundException);
    }

    /**
     * Zwraca identyfikator encji na podstawie date, jeśli znajdują się tam zewnętrzne klucze
     * @param array $data
     * @param bool $executeNotFoundException
     * @return void
     */
    public function prepareExternalData(array &$data, bool $executeNotFoundException = true): void
    {
        // Sprawdzam, czy istnieją pola
        if(!isset($data['clientId']) && !isset($data['clientIdExternal']) && !isset($data['clientExternalId'])){
            return;
        }

        // Pobieram identyfikator
        $id = $data['clientId'] ?? null;
        $idExternal = $data['clientIdExternal'] ?? $data['clientExternalId'] ?? null;

        $data['clientId'] = $this->clientService->getIdIfExist($id, $idExternal, $executeNotFoundException);

        // Usuwam pola zewnętrzne
        unset($data['clientIdExternal']);
        unset($data['clientExternalId']);
    }

    /**
     * Zwraca id statusu, jeśli istnieje na podstawie tablicy danych
     * @param array $data
     * @return int|null
     */
    public function getClientStatusIdIfExistsByData(array &$data): ?int
    {
        $statusData = CommonDomainDataTransformer::getDataForField($data, 'status');

        $statusDataId = $this->clientStatusService->getStatusIdIfExistsByData($data['status'] ?? null, $statusData);

        CommonDomainDataTransformer::removeDataForField($data,'status');

        return $statusDataId;
    }
}
