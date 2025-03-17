<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\Clients;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonParameters\CommonGetListUiApiParametersDto;

class GetClientsParametersDto extends CommonGetListUiApiParametersDto
{
    #[OA\Property(
        description: 'Filtrowanie na podstawie statusu',
        example: 1,
    )]
    protected ?int $status;

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
