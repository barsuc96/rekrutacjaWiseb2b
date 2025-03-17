<?php

declare(strict_types=1);

namespace Wise\Receiver\Domain\Receiver\Service\Interfaces;

use Wise\Core\Domain\Interfaces\EntityDomainServiceInterface;

interface ReceiverServiceInterface extends EntityDomainServiceInterface
{
    public function getStreetWithNumber($street, $houseNumber): string;
    public function prepareJoins(?array $fieldsArray): array;
}
