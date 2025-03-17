<?php

namespace Wise\Core\Domain\Parameters;

/**
 * Enum definiujący typ parameteru (wykorzystywany między innymi do określenia typu pola w formularzu)
 */
enum ParameterDefinitionType: string
{
    case CHECKBOX = 'checkbox';
    case INPUT_STRING = 'input_string';
    case INPUT_INT = 'input_int';
    case INPUT_FLOAT = 'input_float';
    case INPUT_DATE = 'input_date';
    case ATTACHMENT = 'attachment';

    public function getDefaultValue(): mixed
    {
        return match ($this) {
            self::CHECKBOX => false,
            self::INPUT_STRING => '',
            self::INPUT_INT => 0,
            self::INPUT_FLOAT => 0.0,
            self::INPUT_DATE => null,
            self::ATTACHMENT => null,
            default => null
        };
    }
}
