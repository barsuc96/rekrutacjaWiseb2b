<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\Countries;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;
use Wise\Core\Model\Translations;
use DateTimeInterface;

class GetCountriesQueryParametersDto extends CommonGetAdminApiDto
{
    #[OA\Property(
        description: 'ID (kod) kraju ',
        example: 'pl',
    )]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne kraju',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'Czy kraj aktywny?',
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

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }
}
