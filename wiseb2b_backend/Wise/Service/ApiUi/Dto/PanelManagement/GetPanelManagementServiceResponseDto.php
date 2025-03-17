<?php

declare(strict_types=1);

namespace Wise\Service\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\Dto\AbstractResponseDto;

class GetPanelManagementServiceResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected ?int $id;

    /** @var ServiceTranslationDto[] */
    protected ?array $name = null;

    /** @var ServiceTranslationDto[] */
    protected ?array $description = null;

    #[OA\Property(
        description: 'Nazwa usługi w formie sformatowanej',
        example: 'Dropshipping',
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?string $nameFormatted;

    #[OA\Property(
        description: 'Opis usługi w formie sformatowanej',
        example: 'Opłata za dropshipping',
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?string $descriptionFormatted;

    #[OA\Property(
        description: 'Typ usługi',
        example: 'additional',
    )]
    protected ?string $type;

    #[OA\Property(
        description: 'Metoda obliczania kosztów (np stała, bądź procentowa z wartości koszyka)',
        example: 2,
    )]
    protected ?int $costCalcMethod;

    #[OA\Property(
        description: 'Parametr metody obliczania kosztów',
        example: 5,
    )]
    protected ?float $costCalcParam;

    #[OA\Property(
        description: 'Procent podatku',
        example: 23.0,
    )]
    protected ?float $taxPercent = null;

    #[OA\Property(
        description: 'Nazwa sterownika (m.in wykorzystywane do wyliczenia ostatecznej kwoty)',
        example: 'dropshipping',
    )]
    protected ?string $driverName;

    #[OA\Property(
        description: 'Czy usługa jest aktywna?',
        example: true,
    )]
    protected ?bool $isActive;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

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

    public function getNameFormatted(): ?string
    {
        return $this->nameFormatted;
    }

    public function setNameFormatted(?string $nameFormatted): self
    {
        $this->nameFormatted = $nameFormatted;

        return $this;
    }

    public function getDescriptionFormatted(): ?string
    {
        return $this->descriptionFormatted;
    }

    public function setDescriptionFormatted(?string $descriptionFormatted): self
    {
        $this->descriptionFormatted = $descriptionFormatted;

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

    public function getCostCalcMethod(): ?int
    {
        return $this->costCalcMethod;
    }

    public function setCostCalcMethod(?int $costCalcMethod): self
    {
        $this->costCalcMethod = $costCalcMethod;

        return $this;
    }

    public function getCostCalcParam(): ?float
    {
        return $this->costCalcParam;
    }

    public function setCostCalcParam(?float $costCalcParam): self
    {
        $this->costCalcParam = $costCalcParam;

        return $this;
    }

    public function getTaxPercent(): ?float
    {
        return $this->taxPercent;
    }

    public function setTaxPercent(?float $taxPercent): self
    {
        $this->taxPercent = $taxPercent;

        return $this;
    }

    public function getDriverName(): ?string
    {
        return $this->driverName;
    }

    public function setDriverName(?string $driverName): self
    {
        $this->driverName = $driverName;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
