<?php

declare(strict_types=1);


namespace Wise\Core\Exception;

use Exception;

abstract class CommonApiException extends Exception
{
    protected ?string $messageException = null;
    protected ?string $translationKey = null;
    protected ?array $translationParams = null;

    protected bool $showTranslationMessage = true;

    public function setTranslation($translationKey, $translationParams = []): self
    {
        $this->translationKey = $translationKey;
        $this->translationParams = $translationParams;

        return $this;
    }

    public function getTranslationKey(): ?string
    {
        return $this->translationKey;
    }

    public function getTranslationParams(): ?array
    {
        return $this->translationParams ?? [];
    }

    public function setTranslationParams(?array $translationParams): self
    {
        $this->translationParams = $translationParams;

        return $this;
    }

    public function getResponseMessage(): string
    {
        return $this->getMessage();
    }

    public function getMessageException(): ?string
    {
        return $this->messageException;
    }

    public function setMessageException(?string $messageException): self
    {
        $this->messageException = $messageException;

        return $this;
    }

    public function isShowTranslationMessage(): bool
    {
        return $this->showTranslationMessage;
    }

    public function setShowTranslationMessage(bool $showTranslationMessage): self
    {
        $this->showTranslationMessage = $showTranslationMessage;

        return $this;
    }


}
