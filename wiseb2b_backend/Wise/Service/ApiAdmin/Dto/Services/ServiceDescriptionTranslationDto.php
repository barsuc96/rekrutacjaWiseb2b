<?php

declare(strict_types=1);

namespace Wise\Service\ApiAdmin\Dto\Services;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class ServiceDescriptionTranslationDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id usługi',
        example: 1,
    )]
    #[Ignore]
    protected ?int $serviceId;

    #[OA\Property(
        description: 'Język tłumaczenia skrótu',
        example: 'pl',
    )]
    #[Assert\NotBlank(
        message: "Musisz podać język tłumaczenia"
    )]
    #[Assert\Length(
        min: 2,
        max: 3,
        minMessage: "Język tłumaczenia musi mieć przynajmniej {{ limit }} znaków",
        maxMessage: "Język tłumaczenia może mieć maksymalnie {{ limit }} znaków",
    )]
    protected string $language;

    #[OA\Property(
        description: 'Tłumaczenie opisu usługi',
        example: 'Pakowanie na prezent',
    )]
    #[Assert\NotBlank(
        message: "Musisz podać tłumaczenie opisu usługi"
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Opis usługi może mieć maksymalnie {{ limit }} znaków"
    )]
    protected string $translation;

    public function getServiceId(): ?int
    {
        return $this->serviceId;
    }

    public function setServiceId(?int $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }

    public function setTranslation(string $translation): self
    {
        $this->translation = $translation;

        return $this;
    }
}
