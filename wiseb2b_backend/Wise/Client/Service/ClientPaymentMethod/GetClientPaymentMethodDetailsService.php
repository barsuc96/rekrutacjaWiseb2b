<?php

namespace Wise\Client\Service\ClientPaymentMethod;

use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethodRepositoryInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\GetClientPaymentMethodDetailsServiceInterface;
use Wise\Core\Service\AbstractDetailsService;

class GetClientPaymentMethodDetailsService extends AbstractDetailsService implements GetClientPaymentMethodDetailsServiceInterface
{
    public function __construct(
        private readonly ClientPaymentMethodRepositoryInterface $repository,
    ){
        parent::__construct($repository);
    }
}
