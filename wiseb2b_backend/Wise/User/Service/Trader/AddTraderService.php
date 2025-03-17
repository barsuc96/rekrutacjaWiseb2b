<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectExistsException;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\User\Domain\Trader\Trader;
use Wise\User\Domain\Trader\TraderRepositoryInterface;
use Wise\User\Service\Trader\Interfaces\AddTraderServiceInterface;

class AddTraderService implements AddTraderServiceInterface
{
    public function __construct(
        private readonly TraderRepositoryInterface $repository,
        private readonly DomainEventsDispatcher $eventsDispatcher,
    ) {}

    public function __invoke(CommonModifyParams $traderServiceDto): CommonModifyParams
    {
        $newTraderData = $traderServiceDto->read();
        $id = $newTraderData['id'] ?? null;

        if ($this->repository->findOneBy(['id' => $id])) {
            throw new ObjectExistsException(
                sprintf('Obiekt o id: %s istnieje już w bazie danych.', $id)
            );
        }

        $newTrader = (new Trader())->create($traderServiceDto->read());

        $this->eventsDispatcher->flushInternalEvents();

        $newTrader->validate();
        $newTrader = $this->repository->save($newTrader);

        $this->eventsDispatcher->flush();

        ($resultDTO = new CommonModifyParams())->write($newTrader);

        return $resultDTO;
    }
}
