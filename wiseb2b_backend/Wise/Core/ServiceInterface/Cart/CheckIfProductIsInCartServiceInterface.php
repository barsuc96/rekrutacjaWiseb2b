<?php

declare(strict_types=1);

namespace Wise\Core\ServiceInterface\Cart;

use Wise\Cart\Model\CartModel;
use Wise\Product\Entity\Product;

interface CheckIfProductIsInCartServiceInterface
{
    public function checkIfProductIsInCart(Product $productEntity, CartModel $cart): void;
}