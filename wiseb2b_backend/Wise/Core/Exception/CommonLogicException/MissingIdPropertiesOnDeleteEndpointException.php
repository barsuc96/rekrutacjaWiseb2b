<?php

declare(strict_types=1);

namespace Wise\Core\Exception\CommonLogicException;

use Wise\Core\Exception\CommonLogicException;

class MissingIdPropertiesOnDeleteEndpointException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.missing_id_properties_on_delete_endpoint';
}
