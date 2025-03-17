<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto;

use OpenApi\Attributes as OA;

class TermsDto
{
    #[OA\Property(
        description: 'Nagłówek',
        example: 'Regulamin Platformy Wiseb2b.eu',
    )]
    protected string $header;

    #[OA\Property(
        description: 'Nagłówek podrzędny',
        example: '(obowiązuje od dnia 01.01.2025 r.)',
    )]
    protected string $subHeader;

    #[OA\Property(
        description: 'Treść regulaminu',
        example: 'Treść zgody na przetwarzanie danych'
    )]
    protected string $content;

    public function getHeader(): string
    {
        return $this->header;
    }

    public function setHeader(string $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getSubHeader(): string
    {
        return $this->subHeader;
    }

    public function setSubHeader(string $subHeader): self {
        $this->subHeader = $subHeader;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
