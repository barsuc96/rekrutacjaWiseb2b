<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class PostPanelManagementClientDeliveryMethodDto extends CommonUiApiDto
{

    #[OA\Property(
        description: 'Metoda dostawy',
        example: 1,
    )]
    protected ?int $deliveryMethodId;

    #[OA\Property(
        description: 'Identyfikator klienta',
        example: 1,
    )]
    protected ?int $clientId;

    #[OA\Property(
        description: 'Czy encja jest aktywna',
        example: true,
    )]
    protected ?bool $isActive;

    public function getDeliveryMethodId(): ?int
    {
        return $this->deliveryMethodId;
    }

    public function setDeliveryMethodId(?int $deliveryMethodId): self
    {
        $this->deliveryMethodId = $deliveryMethodId;

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

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }


}

