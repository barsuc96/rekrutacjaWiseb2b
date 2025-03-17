<?php

namespace Wise\Core\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class WordsCountValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof WordsCount) {
            throw new UnexpectedTypeException($constraint, WordsCount::class);
        }

        if (null === $value || '' === $value) {
            $this->context->buildViolation($constraint->limitMessage)
                ->setParameter('{{ count }}', 0)
                ->setParameter('{{ limit }}', $constraint->limit)
                ->addViolation();
            return;
        }

        $words = explode(" ", $value);
        $wordsCount = count($words);

        if ($wordsCount !== $constraint->limit) {
            $this->context->buildViolation($constraint->limitMessage)
                ->setParameter('{{ count }}', $wordsCount)
                ->setParameter('{{ limit }}', $constraint->limit)
                ->addViolation();
        }
    }
}
