<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Service;

use Symfony\Component\Uid\Uuid;
use Wise\Core\ApiAdmin\ServiceInterface\RequestUuidServiceInterface;

/**
 * Serwis do obsługi UUID, przechowuje UUID w obrębie DI
 */
class RequestUuidService implements RequestUuidServiceInterface
{
    protected ?string $uuid = null;

    public function create(?string $uuid = null): void
    {
        if (is_null($uuid)) {
            $this->uuid = (string)Uuid::v4();
        } else {
            $this->uuid = $uuid;
        }
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }
}
