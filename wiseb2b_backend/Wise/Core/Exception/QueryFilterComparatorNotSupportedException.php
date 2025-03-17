<?php

declare(strict_types=1);

namespace Wise\Core\Exception;

class QueryFilterComparatorNotSupportedException extends CommonLogicException
{
    public function __construct(string $comparator)
    {
        $message = 'Comparator: '. $comparator .' is not supported for filtering this object';
        parent::__construct($message);
    }
}