<?php

declare(strict_types=1);

namespace Wise\User\Service\User\Interfaces;

use Wise\Client\Domain\Client\Client;
use Wise\User\Domain\Trader\Trader;
use Wise\User\Domain\User\User;

interface UserHelperInterface
{
    public function findUserForModify(array $data): ?User;

    public function getUser(?int $id, ?string $externalId): User;

    public function getClient(array $data): ?Client;

    public function getRole(array $data);

    public function getTrader(array $data): ?Trader;

    /** @return list<User> */
    public function getAllUsersForClient(int $clientId): array;

    public function checkUserExists(int $id = null, string $idExternal = null): bool;

    public function getUserIdIfExistsByData(?int $id = null, ?array $userData = null): ?int;
}
