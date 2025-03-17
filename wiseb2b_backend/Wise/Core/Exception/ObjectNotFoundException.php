<?php

declare(strict_types=1);

namespace Wise\Core\Exception;


class ObjectNotFoundException extends CommonLogicException
{
    protected const OBJECT_EXCEPTION_TRANSLATE_NAME = null;

    protected ?string $translationKey = 'exceptions.default_not_found';

    public static function id(int $id): self
    {
        $translation = 'exceptions.default_not_found';

        if(static::OBJECT_EXCEPTION_TRANSLATE_NAME == null) {
            $translation = static::OBJECT_EXCEPTION_TRANSLATE_NAME;
        }

        return (new self())->setTranslation($translation, ['%id%' => $id]);
    }
}
