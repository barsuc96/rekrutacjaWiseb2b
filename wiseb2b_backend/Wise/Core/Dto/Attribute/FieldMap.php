<?php

declare(strict_types=1);

namespace Wise\Core\Dto\Attribute;


#[\Attribute(\Attribute::TARGET_PROPERTY)]
class FieldMap
{
    public function __construct(
        public string $entityClass,
        public string $entityField,
    )
    {

    }


}