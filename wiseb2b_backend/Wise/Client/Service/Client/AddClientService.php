<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Domain\Client\Factory\ClientFactory;
use Wise\Client\Service\Client\Interfaces\AddClientServiceInterface;
use Wise\Client\Service\Client\Interfaces\ClientGroupHelperInterface;
use Wise\Client\Service\Client\Interfaces\ClientHelperInterface;
use Wise\Core\DataTransformer\CommonDomainDataTransformer;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;
use Wise\User\Service\Trader\Interfaces\TraderHelperInterface;

class AddClientService extends AbstractAddService implements AddClientServiceInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $repository,
        private readonly ClientFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
        private readonly ClientGroupHelperInterface $clientGroupHelper,
        private readonly TraderHelperInterface $traderHelper,
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
        $this->clientGroupHelper->prepareExternalData($data);
        $this->traderHelper->prepareExternalData($data);
        $this->clientHelper->prepareExternalParentClientData($data);

        // Przygotowanie danych dotyczących statusu
        if (CommonDomainDataTransformer::validateFieldInData($data, 'status')) {
            $data['status'] = $this->clientHelper->getClientStatusIdIfExistsByData($data);
        } else {
            CommonDomainDataTransformer::removeDataForField($data, 'status.');
        }

        return $data;
    }
}
