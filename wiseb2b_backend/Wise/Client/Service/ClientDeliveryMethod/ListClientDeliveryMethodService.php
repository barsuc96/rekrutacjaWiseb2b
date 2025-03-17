<?php

namespace Wise\Client\Service\ClientDeliveryMethod;

use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethodRepositoryInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\ListClientDeliveryMethodServiceInterface;
use Wise\Core\Service\AbstractListService;

class ListClientDeliveryMethodService extends AbstractListService implements ListClientDeliveryMethodServiceInterface
{
    public function __construct(
        private readonly ClientDeliveryMethodRepositoryInterface $repository
    )
    {
        parent::__construct($repository);
    }
}
