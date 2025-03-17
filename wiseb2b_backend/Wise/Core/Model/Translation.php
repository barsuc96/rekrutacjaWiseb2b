<?php

declare(strict_types=1);

namespace Wise\Core\Model;

class Translation extends AbstractModel
{
    protected string $language;
    protected string $translation;

    public static function fromArray(array $data): Translation
    {
        return (new self())
            ->setLanguage($data['language'])
            ->setTranslation($data['translation'])
        ;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): Translation
    {
        $this->language = $language;

        return $this;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }

    public function setTranslation(string $translation): Translation
    {
        $this->translation = $translation;

        return $this;
    }
}
