<?php

declare(strict_types=1);


namespace Wise\User\Domain\Trader;

use Wise\Core\Domain\Interfaces\EntityDomainServiceInterface;

interface TraderServiceInterface extends EntityDomainServiceInterface
{
    public function getName($firstName, $lastName): string;
}
