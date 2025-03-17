<?php

declare(strict_types=1);

namespace Wise\Core\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CountryCodeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CountryCode) {
            throw new UnexpectedTypeException($constraint, CountryCode::class);
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

        // Przykład: Numer składający się z 9 cyfr lub z kierunkowym i numerem
        return preg_match('/^\d{9}$/', $value) || preg_match('/^\+\d{11,}$/', $value);
    }
}
