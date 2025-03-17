<?php

namespace Wise\Service\ApiUi\Dto\PanelManagement;

use Symfony\Component\Serializer\Annotation\Ignore;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

class ServiceTranslationDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id metody dostawy',
        example: 'XYZ-ABC-1234',
    )]
    #[Ignore]
    protected ?string $serviceId;

    #[OA\Property(
        description: 'Język tłumaczenia',
        example: 'pl',
    )]
    protected ?string $language;

    #[OA\Property(
        description: 'Tłumaczenie nazwy produktu',
        example: 'Umowa - regulamin 2024.11.24',
    )]
    protected ?string $translation;

    public function getServiceId(): ?string
    {
        return $this->serviceId;
    }

    public function setServiceId(?string $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getTranslation(): ?string
    {
        return $this->translation;
    }

    public function setTranslation(?string $translation): self
    {
        $this->translation = $translation;

        return $this;
    }
}

