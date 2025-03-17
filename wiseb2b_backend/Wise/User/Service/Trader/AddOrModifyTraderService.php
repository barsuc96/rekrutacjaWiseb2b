<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader;

use Wise\Core\Dto\CommonModifyParams;
use Wise\User\Domain\Trader\Trader;
use Wise\User\Service\Trader\Interfaces\AddOrModifyTraderServiceInterface;
use Wise\User\Service\Trader\Interfaces\AddTraderServiceInterface;
use Wise\User\Service\Trader\Interfaces\ModifyTraderServiceInterface;
use Wise\User\Service\Trader\Interfaces\TraderHelperInterface;

class AddOrModifyTraderService implements AddOrModifyTraderServiceInterface
{
    public function __construct(
        private readonly TraderHelperInterface $helper,
        private readonly ModifyTraderServiceInterface $modifyService,
        private readonly AddTraderServiceInterface $addService
    ) {}

    public function __invoke(CommonModifyParams $traderServiceDto): CommonModifyParams
    {
        $data = $traderServiceDto->read();
        $trader = $this->helper->findTraderForModify($data);

        if (true === $trader instanceof Trader) {
            return ($this->modifyService)($traderServiceDto);
        }

        return ($this->addService)($traderServiceDto);
    }
}
