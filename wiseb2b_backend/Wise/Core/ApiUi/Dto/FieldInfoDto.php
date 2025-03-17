<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto;

use JsonSerializable;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Enum\ResponseMessageStyle;

/**
 *  TODO: [ws] dodać opis, nie wiem jak to działa
 */
class FieldInfoDto extends AbstractDto
{
    #[OA\Property(description: 'Wiadomość wyświetlana użytkownikowi', example: 'Nieprawidłowy adres e-mail')]
    protected string $message;
    #[OA\Property(description: 'Styl wiadomości wyświetlanej użytkownikowi', example: 'success')]
    protected string $messageStyle;
    #[OA\Property(description: 'Nazwa pola z błędem', example: 'receiver.email')]
    protected string $propertyPath;
    #[OA\Property(description: 'Nazwa pola - przetłumaczona (do wyświetlenia) ', example: 'E-mail')]
    protected string $propertyName;
    #[OA\Property(description: 'Wartość pola z błędem', example: 'test@test.com')]
    protected ?string $invalidValue;

    #[OA\Property(description: 'Sugerowana wartość - poprawna', example: 'test@email.com')]
    protected ?string $suggestedValue = null;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    public function setPropertyPath(string $propertyPath): self
    {
        $this->propertyPath = $propertyPath;

        return $this;
    }

    public function getInvalidValue(): ?string
    {
        return $this->invalidValue;
    }

    public function setInvalidValue(?string $invalidValue): self
    {
        $this->invalidValue = $invalidValue;

        return $this;
    }

    public function getMessageStyle(): string
    {
        return $this->messageStyle;
    }

    public function setMessageStyle(string $messageStyle): self
    {
        $this->messageStyle = $messageStyle;
        return $this;
    }



    public function toArray(): array
    {
        return [
            'property_path' => $this->isInitialized('propertyPath') ? $this->propertyPath : null,
            'property_name' => $this->isInitialized('propertyName') ? $this->propertyName : null,
            'message' => $this->isInitialized('message') ? $this->message : null,
            'message_style' => $this->isInitialized('messageStyle') ? $this->messageStyle : ResponseMessageStyle::FAILED,
            'invalid_value' => $this->isInitialized('invalidValue') ? $this->invalidValue : null,
            'suggestedValue' => $this->isInitialized('suggestedValue') ? $this->suggestedValue : null,
        ];
    }

    public function getSuggestedValue(): ?string
    {
        return $this->suggestedValue;
    }

    public function setSuggestedValue(?string $suggestedValue): self
    {
        $this->suggestedValue = $suggestedValue;

        return $this;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function setPropertyName(string $propertyName): self
    {
        $this->propertyName = $propertyName;

        return $this;
    }


}
