<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetPanelManagementClientDeliveryMethodDto extends CommonUiApiDto
{

    #[OA\Property(
        description: 'Metoda dostawy',
        example: 1,
    )]
    protected ?int $deliveryMethodId = null;

    #[OA\Property(
        description: 'GLS',
        example: 1,
    )]
    protected ?string $deliveryMethod = null;


    #[OA\Property(
        description: 'Identyfikator encji',
        example: 1,
    )]
    protected ?int $id = null;


    #[OA\Property(
        description: 'Czy encja jest aktywna',
        example: true,
    )]
    protected ?bool $isActive = null;

    public function getDeliveryMethodId(): ?int
    {
        return $this->deliveryMethodId;
    }

    public function setDeliveryMethodId(?int $deliveryMethodId): self
    {
        $this->deliveryMethodId = $deliveryMethodId;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

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

    public function getDeliveryMethod(): ?string
    {
        return $this->deliveryMethod;
    }

    public function setDeliveryMethod(?string $deliveryMethod): self
    {
        $this->deliveryMethod = $deliveryMethod;

        return $this;
    }


}

