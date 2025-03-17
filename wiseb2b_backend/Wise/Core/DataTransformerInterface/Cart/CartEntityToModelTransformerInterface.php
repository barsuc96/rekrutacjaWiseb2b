<?php

declare(strict_types=1);

namespace Wise\Core\DataTransformerInterface\Cart;

use Wise\Cart\Entity\Cart;
use Wise\Cart\Model\CartModel;

/**
 * @deprecated - do usunięcia
 */
interface CartEntityToModelTransformerInterface
{
    public function transform(Cart $cart, array $cartPositions): CartModel;
}