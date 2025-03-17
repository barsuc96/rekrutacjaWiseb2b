<?php

declare(strict_types=1);

namespace Wise\Core\Api\Dto\Attributes;

use Wise\Core\Api\Fields\FieldHandlingEnum;

/**
 * Atrybut umożliwiający zmapowanie zmiennej DTO do pola w encji.
 * Przekazuje nazwe pola w encji.
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class FieldEntityMapping
{
    private string|FieldHandlingEnum|null $entityField;

    public function __construct(string|FieldHandlingEnum|null $mappingToEntityField)
    {
        $this->entityField = $mappingToEntityField;
    }

    public function getEntityField(): string|FieldHandlingEnum|null
    {
        return $this->entityField;
    }
}
