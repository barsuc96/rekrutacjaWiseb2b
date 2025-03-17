<?php

namespace Wise\Core\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PhoneNumber) {
            throw new UnexpectedTypeException($constraint, PhoneNumber::class);
        }

        if (empty($value) || !$this->isValidPhoneNumber($value)) {
            if($value == null){
                $value = '';
            }

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

    protected function isValidPhoneNumber($value)
    {
        // Sprawdź, czy numer telefonu spełnia warunki
        // Możesz dostosować to do własnych wymagań

        // Przykład: Numer składający się z 5-20 cyfr lub z kierunkowym i numerem
        return preg_match('/^\d{5,20}$/', $value) || preg_match('/^\+\d{9,}$/', $value);
    }
}
