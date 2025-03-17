<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientPaymentMethod;

use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethodRepositoryInterface;
use Wise\Client\Service\Client\Helper\Interfaces\ClientHelperInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\AddClientPaymentMethodServiceInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\AddOrModifyClientPaymentMethodServiceInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\ModifyClientPaymentMethodServiceInterface;
use Wise\Core\Service\AbstractAddOrModifyService;

class AddOrModifyClientPaymentMethodService extends AbstractAddOrModifyService implements AddOrModifyClientPaymentMethodServiceInterface
{
    public function __construct(
        private readonly ClientPaymentMethodRepositoryInterface $repository,
        private readonly AddClientPaymentMethodServiceInterface $addService,
        private readonly ModifyClientPaymentMethodServiceInterface $modifyService,
        private readonly ClientHelperInterface $clientHelper,
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }

}
