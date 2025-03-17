<?php

namespace Wise\Agreement\Service\Contract;

use Wise\Agreement\Domain\Contract\Service\Interfaces\ContractServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ContractHelperInterface;
use Wise\Core\Service\AbstractHelper;

class ContractHelper extends AbstractHelper implements ContractHelperInterface
{
    public function __construct(
        private readonly ContractServiceInterface $contractService,
    ){
        parent::__construct(
            entityDomainService: $contractService
        );
    }

    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param array $data
     * @param bool $executeNotFoundException
     * @return int|null
     */
    public function getIdIfExistByDataExternal(array $data, bool $executeNotFoundException = true): ?int
    {
        $id = $data['contractId'] ?? null;
        $idExternal = $data['contractIdExternal'] ?? $data['contractExternalId'] ?? null;

        return $this->contractService->getIdIfExist($id, $idExternal, $executeNotFoundException);
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
        if(!isset($data['contractId']) && !isset($data['contractIdExternal']) && !isset($data['contractExternalId'])){
            return;
        }

        // Pobieram identyfikator
        $id = $data['contractId'] ?? null;
        $idExternal = $data['contractIdExternal'] ?? $data['contractExternalId'] ?? null;

        $data['contractId'] = $this->contractService->getIdIfExist($id, $idExternal, $executeNotFoundException);

        // Usuwam pola zewnętrzne
        unset($data['contractIdExternal']);
        unset($data['contractExternalId']);
    }
}
