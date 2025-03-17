<?php

namespace Wise\Core\Validator\Constraints;

use Wise\Core\Validator\Enum\ConstraintTypeEnum;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Email extends \Symfony\Component\Validator\Constraints\Email
{
    protected ?string $translation = 'constraints.email';

    public function __construct(
        array $options = [],
        string $message = null,
        bool $allowNull = null,
        callable $normalizer = null,
        array $groups = null,
        $payload = null,
        ?string $translation = null,
        ConstraintTypeEnum $constraintType = ConstraintTypeEnum::OK
    )
    {
        $payload['constraintType'] = $constraintType;
        parent::__construct($options, $message, $allowNull, $normalizer, $groups, $payload);
        $this->translation = $translation ?? $this->translation;
    }

    public function validatedBy()
    {
        return parent::class.'Validator';
    }
}
