<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;

use Wise\Core\Service\CommonDetailsParams;

class GetReceiverDetailsParams extends CommonDetailsParams
{
    public const ADDITIONAL_DATA_TYPES = [
    ];

    protected ?int $receiverId = null;

    public function getReceiverId(): ?int
    {
        return $this->receiverId;
    }

    public function setReceiverId(?int $receiverId): self
    {
        $this->receiverId = $receiverId;
        $this->setId($receiverId);

        return $this;
    }
}
