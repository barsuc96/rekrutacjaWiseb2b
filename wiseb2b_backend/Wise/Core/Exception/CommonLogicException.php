<?php

declare(strict_types=1);

namespace Wise\Core\Exception;

use LogicException;

/**
 * Klasa bazowa dla wyjątków logiki biznesowej
 */
class CommonLogicException extends LogicException
{
    /**
     * Wiadomość błędu
     * @var string|null
     */
    protected ?string $messageException = null;

    /**
     * Klucz tłumaczenia wyjątku (wyświetlana w komunikacie np. exceptions.product.not_found)
     * @var string|null
     */
    protected ?string $translationKey = null;

    /**
     * Parametry tłumaczenia
     * @var array|null
     */
    protected ?array $translationParams = null;

    /**
     * Wiadomość będzie doklejona do podstawowej wiadomości wyjątku
     * np. wyświetlam exception o komunikacie iż produkt nie istnieje... i dodatkowo mogę dokleić parametry
     * @var string|null
     */
    protected ?string $additionalMessage = null;

    /**
     * Działa tak samo jak additionalMessage, ale jest wyświetlana tylko w ADMIN API
     * @var string|null
     */
    protected ?string $additionalMessageAdminApi = null;

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

    public function setTranslationParams(?array $translationParams): self
    {
        $this->translationParams = $translationParams;

        return $this;
    }

    public function getAdditionalMessage(): ?string
    {
        return $this->additionalMessage;
    }

    public function setAdditionalMessage(?string $additionalMessage): self
    {
        $this->additionalMessage = $additionalMessage;
        $this->setAdditionalMessageAdminApi($additionalMessage);

        return $this;
    }

    public function getAdditionalMessageAdminApi(): ?string
    {
        return $this->additionalMessageAdminApi;
    }

    public function setAdditionalMessageAdminApi(?string $additionalMessageAdminApi): self
    {
        $this->additionalMessageAdminApi = $additionalMessageAdminApi;

        return $this;
    }
}
