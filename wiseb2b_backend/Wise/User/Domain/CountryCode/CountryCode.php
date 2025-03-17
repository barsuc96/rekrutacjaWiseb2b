<?php

namespace Wise\User\Domain\CountryCode;

use Wise\Core\Model\AbstractModel;

class CountryCode extends AbstractModel
{
    private string $code;
    private string $language;

    public function __construct(string $code, string $language)
    {
        $this->code = $code;
        $this->language = $language;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
}