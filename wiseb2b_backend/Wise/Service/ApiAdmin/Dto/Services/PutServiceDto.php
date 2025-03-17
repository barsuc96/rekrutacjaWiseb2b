<?php

declare(strict_types=1);

namespace Wise\Service\ApiAdmin\Dto\Services;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class PutServiceDto extends AbstractDto
{
    #[OA\Property(
        description: 'ID nadawane przez system ERP',
        example: 'SERVICE-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Id usługi nadawane przez system ERP, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne usługi systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'Typ rodzaju kosztów serwisowych, wykorzystywana wprost w logice biznesowej do naliczania pewnych kosztów.<br>
        [PAYMENT, DELIVERY, RETURN, DROPSHIPPING]',
        example: 'DELIVERY',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Typ usługi, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected ?string $type;

    /** @var ServiceNameTranslationDto[]  */
    protected ?array $name;

    /** @var ServiceDescriptionTranslationDto[]  */
    protected ?array $description;

    #[OA\Property(
        description: 'Czy usługa aktywna?',
        example: true,
    )]
    protected bool $isActive;

    #[OA\Property(
        description: 'Procent podatku za usługę',
        example: 23,
    )]
    protected float $taxPercent;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?array
    {
        return $this->name;
    }

    public function setName(?array $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?array
    {
        return $this->description;
    }

    public function setDescription(?array $description): self
    {
        $this->description = $description;

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

    public function getTaxPercent(): float
    {
        return $this->taxPercent;
    }

    public function setTaxPercent(float $taxPercent): self
    {
        $this->taxPercent = $taxPercent;

        return $this;
    }


}
