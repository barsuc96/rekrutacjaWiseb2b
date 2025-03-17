<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Helper\Object\ObjectMergeHelper;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\User\Domain\Trader\Trader;
use Wise\User\Domain\Trader\TraderRepositoryInterface;
use Wise\User\Service\Trader\Interfaces\ModifyTraderServiceInterface;
use Wise\User\Service\Trader\Interfaces\TraderHelperInterface;

class ModifyTraderService implements ModifyTraderServiceInterface
{
    public function __construct(
        private readonly TraderHelperInterface $helper,
        private readonly TraderRepositoryInterface $repository,
        private readonly DomainEventsDispatcher $eventsDispatcher,
    ) {}

    public function __invoke(CommonModifyParams $traderServiceDto): CommonModifyParams
    {
        $newTraderData = $traderServiceDto->read();
        $trader = $this->helper->findTraderForModify($newTraderData);

        if (!isset($trader) || !($trader instanceof Trader)) {
            throw new ObjectNotFoundException(
                sprintf('Obiekt o id: %s nie istnieje w bazie danych.', $newTraderData['id'])
            );
        }

        $trader = ObjectMergeHelper::merge(
            $trader,
            $newTraderData,
            [],
            $traderServiceDto->getMergeNestedObjects()
        );

        $this->eventsDispatcher->flushInternalEvents();

        $trader->validate();
        $trader = $this->repository->save($trader);

        $this->eventsDispatcher->flush();

        ($resultDTO = new CommonModifyParams())->write($trader);

        return $resultDTO;
    }
}
