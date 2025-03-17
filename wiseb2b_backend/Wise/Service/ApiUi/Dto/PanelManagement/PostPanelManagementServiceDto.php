<?php

namespace Wise\Service\ApiUi\Dto\PanelManagement;

use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

class PostPanelManagementServiceDto extends CommonParametersDto
{
    /** @var ServiceTranslationDto[] */
    protected ?array $name = null;

    /** @var ServiceTranslationDto[] */
    protected ?array $description = null;

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

    public function setCostCalcMethod(null|string|int $costCalcMethod): self
    {
        if(is_string($costCalcMethod)){
            $costCalcMethod = intval($costCalcMethod);
        }

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
