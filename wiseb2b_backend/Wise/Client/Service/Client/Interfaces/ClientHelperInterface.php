<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\Interfaces;

use Wise\Client\Domain\Client\Client;
use Wise\Core\Service\Interfaces\CommonHelperInterface;

interface ClientHelperInterface extends CommonHelperInterface
{
    public function getClientIdIfExists(?int $id, ?string $idExternal): ?int;

    public function getClientIdIfExistsByData(?int $id, ?array $clientData): ?int;

    public function getOrCreateParentClient(array $data): ?Client;

    public function findClientForModify(array $data): ?Client;

    public function prepareExternalParentClientData(array &$data, bool $executeNotFoundException = true): void;

    public function getClientStatusIdIfExistsByData(array &$data): ?int;
}
