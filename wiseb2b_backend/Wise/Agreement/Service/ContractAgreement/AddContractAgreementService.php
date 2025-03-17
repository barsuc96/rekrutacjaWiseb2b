<?php

namespace Wise\Agreement\Service\ContractAgreement;

use Wise\Agreement\Domain\ContractAgreement\ContractAgreementRepositoryInterface;
use Wise\Agreement\Domain\ContractAgreement\Factory\ContractAgreementFactory;
use Wise\Agreement\Service\Contract\Interfaces\ContractHelperInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\AddContractAgreementServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;
use Wise\User\Service\User\Helper\Interfaces\UserHelperInterface;

class AddContractAgreementService extends AbstractAddService implements AddContractAgreementServiceInterface
{
    public function __construct(
        private readonly ContractAgreementRepositoryInterface $repository,
        private readonly ContractAgreementFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
        private readonly UserHelperInterface $userHelper,
        private readonly ContractHelperInterface $contractHelper
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
        $this->userHelper->prepareExternalData($data);
        $this->contractHelper->prepareExternalData($data);
        return $data;
    }
}
