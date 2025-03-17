<?php

declare(strict_types=1);

namespace Wise\Core\ServiceInterface\Cart;

use Wise\Cart\Admin\V2\Dto\AddProductToCartDto;

interface AddProductToCartServiceInterface
{
    public function process(AddProductToCartDto $addProductToCartDto): bool;
}