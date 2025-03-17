<?php

declare(strict_types=1);

namespace Wise\MultiStore\Service\Store;

use Wise\Core\Service\AbstractDetailsService;
use Wise\MultiStore\Domain\Store\StoreRepositoryInterface;
use Wise\MultiStore\Service\Store\Interfaces\GetStoreDetailsServiceInterface;

class GetStoreDetailsService extends AbstractDetailsService implements GetStoreDetailsServiceInterface
{
    public function __construct(
        private readonly StoreRepositoryInterface $storeRepository
    ){
        parent::__construct($storeRepository);
    }
}
