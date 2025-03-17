<?php

namespace Wise\Core\Validator\Constraints;

use Wise\Core\Validator\Enum\ConstraintTypeEnum;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class NotBlank extends \Symfony\Component\Validator\Constraints\NotBlank
{
    protected ?string $translation = 'constraints.not_blank';

    public function __construct(
        array $options = [],
        string $message = null,
        bool $allowNull = null,
        callable $normalizer = null,
        array $groups = null,
        $payload = null,
        ?string $translation = null
    )
    {
        if(empty($payload)){
            $payload = ["constraintType" => ConstraintTypeEnum::ERROR];
        }

        parent::__construct($options, $message, $allowNull, $normalizer, $groups, $payload);
        $this->translation = $translation ?? $this->translation;
    }

    public function validatedBy()
    {
        return parent::class.'Validator';
    }

}
