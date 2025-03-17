<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;

/**
 * Wspólne dto do endpointów typu DELETE w ADMIN API
 */
class CommonAdminApiDeleteParametersDto extends CommonAdminApiParametersDto
{
    // ==== PARAMETERS ====

    #[OA\Path(
        description: 'Identyfikator encji',
        example: 1,
    )]
    #[FieldEntityMapping('idExternal')]
    protected string $id;



    // ==== RESPONSE ====


    #[OA\Property(
        description: 'Status operacji',
        example: 1,
    )]
    protected int $status;

    #[OA\Property(
        description: 'Status operacji',
        example: 'SUCCESS',
    )]
    protected string $message;









    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }


}
