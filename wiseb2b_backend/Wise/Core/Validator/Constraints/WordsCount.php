<?php

namespace Wise\Core\Validator\Constraints;

use JsonSchema\Constraints\ConstraintInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class WordsCount extends Constraint
{
    public $limitMessage = 'The string must contain exactly {{ limit }} words, but it has {{ count }} words.';
    public $limit;

    public function __construct(
        $limit,
        $options = [],
        array $groups = null,
        $payload = null,
        string $limitMessage = null,
        ConstraintTypeEnum $constraintType = ConstraintTypeEnum::OK
    )
    {
        $this->limit = $limit;
        $this->limitMessage = $limitMessage ?? $this->limitMessage;
        $payload['constraintType'] = $constraintType;
        parent::__construct($options, $groups, $payload);

        if (null === $this->limit) {
            throw new MissingOptionsException(sprintf('Option "limit" must be given for constraint %s', __CLASS__), ['limit']);
        }
    }
}
