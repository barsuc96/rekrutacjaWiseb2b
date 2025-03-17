<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto;

use Wise\Core\ApiUi\Dto\CommonPostUiApiDto;

class PostClientAcceptRequestDto extends CommonPostUiApiDto
{

    protected int $clientId;

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }
}
