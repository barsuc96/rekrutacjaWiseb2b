<?php

namespace Wise\Core\Validator\Constraints;

use Wise\Core\Validator\Enum\ConstraintTypeEnum;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Length extends \Symfony\Component\Validator\Constraints\Length
{
    public function __construct(
        $exactly = null,
        int $min = null,
        int $max = null,
        string $charset = null,
        callable $normalizer = null,
        string $exactMessage = null,
        string $minMessage = null,
        string $maxMessage = null,
        string $charsetMessage = null,
        array $groups = null,
        $payload = null,
        array $options = [],
        ConstraintTypeEnum $constraintType = ConstraintTypeEnum::OK
    )
    {
        $payload['constraintType'] = $constraintType;
        parent::__construct($exactly, $min, $max, $charset, $normalizer, $exactMessage, $minMessage, $maxMessage, $charsetMessage, $groups, $payload, $options);
    }

    public function validatedBy()
    {
        return parent::class.'Validator';
    }
}
