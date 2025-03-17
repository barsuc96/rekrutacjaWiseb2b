<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Service\Client\Interfaces\ClientGroupHelperInterface;
use Wise\Client\Service\Client\Interfaces\ClientHelperInterface;
use Wise\Client\Service\Client\Interfaces\ModifyClientServiceInterface;
use Wise\Core\DataTransformer\CommonDomainDataTransformer;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractModifyService;
use Wise\User\Service\Trader\Interfaces\TraderHelperInterface;

class ModifyClientService extends AbstractModifyService implements ModifyClientServiceInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $repository,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
        private readonly ClientGroupHelperInterface $clientGroupHelper,
        private readonly TraderHelperInterface $traderHelper,
        private readonly ClientHelperInterface $clientHelper,
    ){
        parent::__construct($repository, $persistenceShareMethodsHelper);
    }

    /**
     * Przygotowanie danych przed połączeniem ich z encją za pomocą Merge Service
     * @param array|null $data
     * @param AbstractEntity $entity
     * @return void
     */
    protected function prepareDataBeforeMergeData(?array &$data, AbstractEntity $entity): void
    {
        $this->clientGroupHelper->prepareExternalData($data);
        $this->traderHelper->prepareExternalData($data);
        $this->clientHelper->prepareExternalParentClientData($data);

        // Przygotowanie danych dotyczących statusu
        if (CommonDomainDataTransformer::validateFieldInData($data, 'status')) {
            $data['status'] = $this->clientHelper->getClientStatusIdIfExistsByData($data);
        } else {
            CommonDomainDataTransformer::removeDataForField($data, 'status.');
        }

        return;
    }
}
