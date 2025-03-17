<?php

declare(strict_types=1);

namespace Wise\I18n\ApiUi\Dto\Layout;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class LayoutLanguagesResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator języka',
        example: 'pl',
    )]
    protected string $id;

    #[OA\Property(
        description: 'Nazwa języka',
        example: 'Polski',
    )]
    protected string $name;

    #[OA\Property(
        description: 'Nagłówek strony',
        example: 'WiseB2B - Platforma B2',
    )]
    protected ?string $titleWebsite = null;

    #[OA\Property(
        description: 'Opis strony - meta description',
        example: 'WiseB2B – innowacyjna platforma B2B łącząca dostawców i kupujących',
    )]
    protected ?string $descriptionWebsite = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTitleWebsite(): ?string
    {
        return $this->titleWebsite;
    }

    public function setTitleWebsite(?string $titleWebsite): self
    {
        $this->titleWebsite = $titleWebsite;

        return $this;
    }

    public function getDescriptionWebsite(): ?string
    {
        return $this->descriptionWebsite;
    }

    public function setDescriptionWebsite(?string $descriptionWebsite): self
    {
        $this->descriptionWebsite = $descriptionWebsite;

        return $this;
    }
}
