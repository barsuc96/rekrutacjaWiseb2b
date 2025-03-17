<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientDeliveryMethod;

use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethodRepositoryInterface;
use Wise\Client\Domain\ClientDeliveryMethod\Factory\ClientDeliveryMethodFactory;
use Wise\Client\Service\Client\Helper\Interfaces\ClientHelperInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\AddClientDeliveryMethodServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;

class AddClientDeliveryMethodService extends AbstractAddService implements AddClientDeliveryMethodServiceInterface
{

    public function __construct(
        private readonly ClientDeliveryMethodRepositoryInterface $repository,
        private readonly ClientDeliveryMethodFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
        private readonly ClientHelperInterface $clientHelper,
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
        $this->clientHelper->prepareExternalData($data);

        return $data;
    }


}
