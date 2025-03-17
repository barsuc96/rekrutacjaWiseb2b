<?php

declare(strict_types=1);

namespace Wise\MultiStore\Service\Store;

use Wise\Core\Service\AbstractListService;
use Wise\MultiStore\Domain\Store\StoreRepositoryInterface;
use Wise\MultiStore\Service\Store\Interfaces\ListStoreServiceInterface;

class ListStoreService extends AbstractListService implements ListStoreServiceInterface
{
    public function __construct(
        private readonly StoreRepositoryInterface $storeRepository
    ){
        parent::__construct($storeRepository);
    }
}
