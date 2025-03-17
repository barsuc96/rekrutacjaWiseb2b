<?php

namespace Wise\Client\Service\ClientDeliveryMethod;

use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethodRepositoryInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\GetClientDeliveryMethodDetailsServiceInterface;
use Wise\Core\Service\AbstractDetailsService;

class GetClientDeliveryMethodDetailsService extends AbstractDetailsService implements GetClientDeliveryMethodDetailsServiceInterface
{
    public function __construct(
        private readonly ClientDeliveryMethodRepositoryInterface $repository,
    ){
        parent::__construct($repository);
    }
}
