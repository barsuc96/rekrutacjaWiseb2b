<?php

declare(strict_types=1);

namespace Wise\Core\Exception;

use Wise\Core\ApiUi\Dto\FieldInfoDto;

class DomainValidatorException extends CommonLogicException
{
    public function __construct(
        private readonly string $field,
        private readonly string $validationMessage,
        private readonly mixed $currentValue,
        private readonly string $responseMessage,
    ) {
        parent::__construct();
    }

    public function getViolation(): FieldInfoDto
    {
        return (new FieldInfoDto())
            ->setPropertyPath($this->field)
            ->setMessage($this->validationMessage)
            ->setInvalidValue($this->currentValue);
    }

    public function getResponseMessage(): string
    {
        return $this->responseMessage;
    }
}
