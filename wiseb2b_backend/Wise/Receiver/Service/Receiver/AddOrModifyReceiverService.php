<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Service\AbstractAddOrModifyService;
use Wise\Receiver\Domain\Receiver\Receiver;
use Wise\Receiver\Domain\Receiver\ReceiverRepositoryInterface;
use Wise\Receiver\Service\Receiver\Interfaces\AddOrModifyReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\AddReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ModifyReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverHelperInterface;

class AddOrModifyReceiverService extends AbstractAddOrModifyService implements AddOrModifyReceiverServiceInterface
{

    public function __construct(
        private readonly ReceiverRepositoryInterface $repository,
        private readonly AddReceiverServiceInterface $addService,
        private readonly ModifyReceiverServiceInterface $modifyService,
        private readonly ReceiverHelperInterface $receiverHelper,
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }

    /**
     * Pobranie na podstawie danych z dto, informacji czy encja istnieje
     * @param array|null $data
     * @return bool
     */
    protected function checkEntityExists(?array $data): bool
    {
        $receiver = $this->receiverHelper->findReceiverForModify($data);

        return $receiver instanceof Receiver;
    }
}
