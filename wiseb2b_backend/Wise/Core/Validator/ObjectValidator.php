<?php

declare(strict_types=1);

namespace Wise\Core\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\ObjectValidationException;
use Wise\Core\Model\AbstractModel;

// TODO: Załączać przez interfejs
//TODO JCZ: to chyba jest do wywalenia, bo chyba nie używane nigdzie ? Mozę pułapkę postawić?
class ObjectValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function validate(AbstractModel|AbstractDto $dto): bool
    {
        $errors = $this->validator->validate($dto);

        if ($errors->count() === 0) {
            return true;
        }

        throw new ObjectValidationException($errors->offsetGet(0)->getMessage());
    }
}
