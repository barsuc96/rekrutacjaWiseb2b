<?php

declare(strict_types=1);

namespace Wise\User\Domain\Agreement\Exceptions;

use Wise\Core\Exception\CommonLogicException;

class AgreementRequiredException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.agreement.required';

    public static function content(string $content): self
    {
        return (new self())->setTranslation('exceptions.agreement.required_with_content', ['%content%' => $content]);
    }
}
