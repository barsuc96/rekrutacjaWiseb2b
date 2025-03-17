<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\Countries;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Ignore;
use Wise\Core\ApiAdmin\Dto\DateInsertUpdateDtoTrait;
use Wise\Core\Dto\AbstractDto;
use Symfony\Component\Validator\Constraints as Assert;

class GetCountryNameTranslationResponseDto extends AbstractDto
{
    use DateInsertUpdateDtoTrait;

    #[OA\Property(
        description: 'Id państwa',
        example: 1,
    )]
    #[Ignore]
    protected ?int $countryId;

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
        description: 'Tłumaczenie nazwy usługi',
        example: 'Pakowanie',
    )]
    #[Assert\NotBlank(
        message: "Musisz podać tłumaczenie nazwy usługi"
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Nazwa usługi może mieć maksymalnie {{ limit }} znaków"
    )]
    protected string $translation;

    public function getCountryId(): ?int
    {
        return $this->countryId;
    }

    public function setCountryId(?int $countryId): self
    {
        $this->countryId = $countryId;
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
