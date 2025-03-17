<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\Countries;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class PutCountryDto extends AbstractDto
{
    #[OA\Property(
        description: 'Symbol ISO kraju: PL, EN',
        example: 'pl',
    )]
    #[Assert\Length(
        max: 3,
        maxMessage: 'Symbol ISO kraju, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected int $internalId;

    /** @var PutCountryNameTranslationDto[] */
    protected array $name;

    #[OA\Property(
        description: 'isActive',
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

    public function getName(): array
    {
        return $this->name;
    }

    public function setName(array $name): self
    {
        $this->name = $name;

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
