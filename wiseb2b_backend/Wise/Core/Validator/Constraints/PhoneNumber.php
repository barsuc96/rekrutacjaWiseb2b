<?php

namespace Wise\Core\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class PhoneNumber extends Constraint
{
    public $message = 'constraints.phone_number';

    public function __construct(
        $options = [],
        array $groups = null,
        $payload = null,
        ConstraintTypeEnum $constraintType = ConstraintTypeEnum::ERROR
    )
    {
        $payload['constraintType'] = $constraintType;
        parent::__construct($options, $groups, $payload);
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
