<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Agreements;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class AgreementContentTranslationDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id zgody',
        example: 1,
    )]
    #[Ignore]
    protected ?int $agreementId;

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
        description: 'Tłumaczenie treści zgody',
        example: 'Lorem ipsum...',
    )]
    #[Assert\NotBlank(
        message: "Musisz podać tłumaczenie treści zgody"
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Treść zgody może mieć maksymalnie {{ limit }} znaków"
    )]
    protected string $translation;

    public function getAgreementId(): ?int
    {
        return $this->agreementId;
    }

    public function setAgreementId(?int $agreementId): self
    {
        $this->agreementId = $agreementId;

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
