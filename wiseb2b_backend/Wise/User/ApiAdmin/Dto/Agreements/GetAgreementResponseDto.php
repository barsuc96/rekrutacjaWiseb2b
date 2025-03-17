<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Agreements;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\DateInsertUpdateDtoTrait;
use Wise\Core\Dto\AbstractResponseDto;

class GetAgreementResponseDto extends AbstractResponseDto
{
    use DateInsertUpdateDtoTrait;

    #[OA\Property(
        description: 'ID nadawane przez system ERP',
        example: 'AGREEMENT-123',
    )]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected int $internalId;

    /** @var AgreementNameTranslationDto[] */
    protected array $name;

    /** @var AgreementContentTranslationDto[] */
    protected array $content;

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

    public function getName(): array
    {
        return $this->name;
    }

    public function setName(array $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getIsRequired(): bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): self
    {
        $this->isRequired = $isRequired;

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
