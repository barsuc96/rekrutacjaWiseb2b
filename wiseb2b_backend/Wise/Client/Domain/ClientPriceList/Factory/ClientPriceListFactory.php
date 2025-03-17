<?php

namespace Wise\Client\Domain\ClientPriceList\Factory;

use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Service\Merge\MergeService;

class ClientPriceListFactory extends AbstractEntityFactory
{
    protected const FACTORY_FOR_MODEL = true;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
    ){
        parent::__construct($entity, $mergeService);
    }
}
