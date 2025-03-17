<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use Wise\Core\Model\AbstractModel;

/**
 * Dto zwracany przez wszystkie tłumaczenia obiektów
 */
class CommonTranslationResponseDto extends AbstractModel
{
    public function __construct(
        protected string $language,
        protected string $translation
    ) {
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): CommonTranslationResponseDto
    {
        $this->language = $language;
        return $this;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }

    public function setTranslation(string $translation): CommonTranslationResponseDto
    {
        $this->translation = $translation;
        return $this;
    }
}