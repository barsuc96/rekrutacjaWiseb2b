<?php

declare(strict_types=1);

namespace Wise\Core\Api\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class CommonUiApiParametersDto extends AbstractDto
{
    #[OA\Parameter(description: 'Wersja językowa', in: 'header', example: 'pl')]
    protected string $contentLanguage;

    public function getContentLanguage(): string
    {
        return $this->contentLanguage;
    }

    public function setContentLanguage(string $contentLanguage): self
    {
        $this->contentLanguage = $contentLanguage;

        return $this;
    }
}
