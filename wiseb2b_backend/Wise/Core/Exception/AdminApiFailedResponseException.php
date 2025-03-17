<?php

declare(strict_types=1);

namespace Wise\Core\Exception;

class AdminApiFailedResponseException extends \Exception
{
    private ?CommonLogicException $commonLogicException = null;

    public function __construct(CommonLogicException $commonLogicException)
    {
        $this->commonLogicException = $commonLogicException;
        parent::__construct($commonLogicException->getMessage(), $commonLogicException->getCode(), $commonLogicException);
    }
}
