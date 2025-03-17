<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;

use Wise\Client\Service\Client\Helper\Interfaces\ClientHelperInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;
use Wise\Receiver\Domain\Receiver\Factory\ReceiverFactory;
use Wise\Receiver\Domain\Receiver\ReceiverRepositoryInterface;
use Wise\Receiver\Service\Receiver\Interfaces\AddReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverHelperInterface;

class AddReceiverService extends AbstractAddService implements AddReceiverServiceInterface
{
    public function __construct(
        private readonly ReceiverRepositoryInterface $repository,
        private readonly ReceiverFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
        private readonly ReceiverHelperInterface $receiverHelper,
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
        if(!empty($data['clientId']) || !empty($data['clientIdExternal'])){
            $clientId = $this->clientHelper->getIdIfExistByDataExternal($data);

            unset($data['clientIdExternal']);
            $data['clientId'] = $clientId;
        }

        return $data;
    }
}
