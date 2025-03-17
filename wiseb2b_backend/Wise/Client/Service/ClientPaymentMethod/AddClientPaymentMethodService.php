<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientPaymentMethod;

use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethodRepositoryInterface;
use Wise\Client\Domain\ClientPaymentMethod\Factory\ClientPaymentMethodFactory;
use Wise\Client\Service\Client\Helper\Interfaces\ClientHelperInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\AddClientPaymentMethodServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;

class AddClientPaymentMethodService extends AbstractAddService implements AddClientPaymentMethodServiceInterface
{
    public function __construct(
        private readonly ClientPaymentMethodRepositoryInterface $repository,
        private readonly ClientPaymentMethodFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
        private readonly ClientHelperInterface $clientHelper,
    ) {
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
