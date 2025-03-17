<?php

namespace Wise\Security\Exception;

use Wise\Core\Exception\CommonLogicException;

class OverLoginOnlyOneTimeException extends CommonLogicException
{
    protected ?string $translationKey = 'exceptions.over_login_only_one_time_exception';
}
