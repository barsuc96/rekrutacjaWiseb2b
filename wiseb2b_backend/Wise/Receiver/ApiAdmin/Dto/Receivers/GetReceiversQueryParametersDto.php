<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiAdmin\Dto\Receivers;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;

class GetReceiversQueryParametersDto extends CommonGetAdminApiDto
{
    #[OA\Property(
        description: 'Id nadane z ERP',
        example: '1',
    )]
    protected string $id;
    
    #[OA\Property(
        description: 'Id klienta nadane z ERP',
        example: '1',
    )]
    protected string $clientId;
    
    #[OA\Property(
        description: 'Czy odbiorca jest domyślnym odbiorcą. Będzie domyślnie ustawiany przy składaniu zamówienia',
        example: true,
    )]
    protected bool $isDefault;
    
    #[OA\Property(
        description: 'Czy odbiorca jest aktywny',
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

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }

    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;
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
