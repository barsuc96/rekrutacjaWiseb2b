<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Agreements;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;

class GetAgreementsQueryParametersDto extends CommonGetAdminApiDto
{
    #[OA\Property(
        description: 'ID zewnętrzne zgody',
        example: 'AGREEMENT-123',
    )]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne zgody',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'Czy zgoda wymagana?',
        example: true,
    )]
    protected bool $isRequired;

    #[OA\Property(
        description: 'Czy zgoda aktywna?',
        example: true,
    )]
    protected bool $isActive;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInternalId(): int
    {
        return $this->internalId;
    }

    public function setInternalId(int $internalId): self
    {
        $this->internalId = $internalId;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): self
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
