<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client;

use Wise\Core\Domain\Interfaces\EntityDomainServiceInterface;

interface ClientServiceInterface extends EntityDomainServiceInterface
{
    public function getOrCreateClient($clientId, $clientIdExternal): Client;

    public function getStreetWithNumber($street, $houseNumber): string;

    public function prepareJoins(?array $fieldsArray): array;
    public function getRegisterAddressEntityFieldName(): string;
}
