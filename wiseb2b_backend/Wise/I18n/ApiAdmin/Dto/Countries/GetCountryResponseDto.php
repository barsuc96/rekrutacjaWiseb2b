<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\Countries;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\DateInsertUpdateDtoTrait;
use Wise\Core\Dto\AbstractResponseDto;

class GetCountryResponseDto extends AbstractResponseDto
{
    use DateInsertUpdateDtoTrait;

    #[OA\Property(
        description: 'Symbol ISO kraju: PL, EN',
        example: 'pl',
    )]
    protected string $id;

    /** @var GetCountryNameTranslationResponseDto[]  */
    protected array $name;
    
    #[OA\Property(
        description: 'Czy kraj jest dostępny na witrynie?',
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
