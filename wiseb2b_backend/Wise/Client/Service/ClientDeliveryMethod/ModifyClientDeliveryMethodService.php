<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientDeliveryMethod;

use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethodRepositoryInterface;
use Wise\Client\Service\Client\Helper\Interfaces\ClientHelperInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\ModifyClientDeliveryMethodServiceInterface;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractModifyService;

class ModifyClientDeliveryMethodService extends AbstractModifyService implements ModifyClientDeliveryMethodServiceInterface
{
    public function __construct(
        private readonly ClientDeliveryMethodRepositoryInterface $repository,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
        private readonly ClientHelperInterface $clientHelper,
    ) {
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
        $this->clientHelper->prepareExternalData($data);
    }
}
