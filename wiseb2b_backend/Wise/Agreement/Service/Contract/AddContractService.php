<?php

namespace Wise\Agreement\Service\Contract;

use Wise\Agreement\Domain\Contract\ContractRepositoryInterface;
use Wise\Agreement\Domain\Contract\Factory\ContractFactory;
use Wise\Agreement\Service\Contract\Interfaces\AddContractServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;

class AddContractService extends AbstractAddService implements AddContractServiceInterface
{
    public function __construct(
        private readonly ContractRepositoryInterface $repository,
        private readonly ContractFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $entityFactory, $persistenceShareMethodsHelper);
    }

    /**
     * Umożliwia przygotowanie danych do utworzenia encji w fabryce.
     * @param array|null $data
     * @return array
     */
    protected function prepareDataBeforeCreateEntity(?array &$data): array
    {
        return $data;
    }
}
