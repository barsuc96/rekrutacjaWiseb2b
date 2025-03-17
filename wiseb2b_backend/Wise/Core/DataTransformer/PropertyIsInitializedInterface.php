<?php

declare(strict_types=1);

namespace Wise\Core\DataTransformer;

use Wise\Cart\Entity\Cart;
use Wise\Cart\Model\CartModel;


interface PropertyIsInitializedInterface
{
    public function isInitialized(string $property): bool;
}