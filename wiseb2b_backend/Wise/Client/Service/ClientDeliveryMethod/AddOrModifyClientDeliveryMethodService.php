<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientDeliveryMethod;

use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethodRepositoryInterface;
use Wise\Client\Service\Client\Helper\Interfaces\ClientHelperInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\AddClientDeliveryMethodServiceInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\AddOrModifyClientDeliveryMethodServiceInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\ModifyClientDeliveryMethodServiceInterface;
use Wise\Core\Service\AbstractAddOrModifyService;

class AddOrModifyClientDeliveryMethodService extends AbstractAddOrModifyService implements AddOrModifyClientDeliveryMethodServiceInterface
{
    public function __construct(
        private readonly ClientDeliveryMethodRepositoryInterface $repository,
        private readonly AddClientDeliveryMethodServiceInterface $addService,
        private readonly ModifyClientDeliveryMethodServiceInterface $modifyService,
        private readonly ClientHelperInterface $clientHelper
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }
}
