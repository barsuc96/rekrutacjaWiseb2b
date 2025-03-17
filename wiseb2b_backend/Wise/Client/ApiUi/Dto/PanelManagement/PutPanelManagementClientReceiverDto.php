<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class PutPanelManagementClientReceiverDto extends PostPanelManagementClientReceiverDto
{
    #[OA\Property(
        description: 'Identyfikator',
        example: 1,
    )]
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }


}

