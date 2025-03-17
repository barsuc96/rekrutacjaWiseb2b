<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetPanelManagementClientPaymentMethodDto extends CommonUiApiDto
{

    #[OA\Property(
        description: '',
        example: 1,
    )]
    protected ?int $paymentMethodId = null;


    #[OA\Property(
        description: 'Metoda płatności dostawy',
        example: 'Autopay',
    )]
    protected ?string $paymentMethod = null;


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



    public function getPaymentMethodId(): ?int
    {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(?int $paymentMethodId): self
    {
        $this->paymentMethodId = $paymentMethodId;

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

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }
}

