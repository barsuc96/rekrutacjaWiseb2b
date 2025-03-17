<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Client\Service\Client\Helper\Interfaces\ClientHelperInterface;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Helper\Object\ObjectMergeHelper;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Model\Address;
use Wise\Core\Repository\RepositoryInterface;
use Wise\Core\Service\AbstractModifyService;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\Merge\MergeService;
use Wise\Core\Service\Validator\ValidatorServiceInterface;
use Wise\Receiver\Domain\Receiver\Exceptions\ReceiverNotFoundException;
use Wise\Receiver\Domain\Receiver\Receiver;
use Wise\Receiver\Domain\Receiver\ReceiverRepositoryInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ModifyReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverHelperInterface;

class ModifyReceiverService extends AbstractModifyService implements ModifyReceiverServiceInterface
{
    protected const OBJECT_NOT_FOUND_EXCEPTION = ReceiverNotFoundException::class;

    public function __construct(
        private readonly ReceiverRepositoryInterface $repository,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
        private readonly ReceiverHelperInterface $receiverHelper,
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
        if(!empty($data['clientId']) || !empty($data['clientIdExternal'])){
            $clientId = $this->clientHelper->getIdIfExistByDataExternal($data);

            unset($data['clientIdExternal']);
            $data['clientId'] = $clientId;
        }
    }

    /**
     * Pobranie na podstawie danych z dto, encji z bazy danych.
     * @param array|null $data
     * @return AbstractEntity|null
     */
    protected function getEntity(?array $data): ?AbstractEntity
    {
        return $this->receiverHelper->findReceiverForModify($data);
    }
}
