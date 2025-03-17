<?php

namespace Wise\Core\Notifications;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;
use Wise\Core\ApiUi\Dto\FieldInfoDto;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;

class Notification
{
    public ConstraintTypeEnum $type;

    public ?string $message = null;
    /**
     * Flaga, czy dana notyfikacja jest powiązana z polem obiektu biznesowego,
     */
    public bool $isFieldRelated = false;

    public ?string $objectName;
    public ?string $fieldName;

    public ?ConstraintViolation $constraintViolation;

    /**
     * Prefix ustawiany manualnie w kodzie za pomocą NotificationManager, który określa jaki prefix ma być dodany do propertyPath w FieldInfo
     * @var string|null
     */
    public ?string $prefixPropertyPath = '';


    // error fields wszystkie które dotycza pola i zmienic na fields (response)
    // message wszystkie ktore nie dotycza pola
    public static function fromConstraintViolation(ConstraintViolation $constraintViolation, ConstraintTypeEnum $constraintType, ?string $objectName = null): self
    {
        return new Notification(
            constraintType: $constraintType,
            message: $constraintViolation->getMessage(),
            isFieldRelated: true,
            objectName: ($objectName !== null) ? $objectName : $constraintViolation->getRoot()::class,
            constraintViolation: $constraintViolation
        );
    }

    public function __construct(ConstraintTypeEnum $constraintType, string $message, bool $isFieldRelated, ?string $objectName, ?ConstraintViolation $constraintViolation)
    {
        $this->type = $constraintType;
        $this->message = $message;
        $this->isFieldRelated = $isFieldRelated;
        $this->objectName = $objectName;
        $this->constraintViolation = $constraintViolation;
    }

    public function getConstraintViolation(): ?ConstraintViolation
    {
        return $this->constraintViolation;
    }

    public function getConstraintType(): ConstraintTypeEnum
    {
        return $this->type;
    }

    public function isFieldRelated(): bool
    {
        return $this->isFieldRelated;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPrefixPropertyPath(): ?string
    {
        return $this->prefixPropertyPath;
    }

    public function setPrefixPropertyPath(?string $prefixPropertyPath): self
    {
        $this->prefixPropertyPath = $prefixPropertyPath;

        return $this;
    }
}
