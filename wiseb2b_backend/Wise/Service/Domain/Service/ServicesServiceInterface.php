<?php

declare(strict_types=1);


namespace Wise\Service\Domain\Service;

use Wise\Core\Domain\Interfaces\EntityDomainServiceInterface;

interface ServicesServiceInterface extends EntityDomainServiceInterface
{
    public function findServiceForModify(array $data): ?Service;
}
