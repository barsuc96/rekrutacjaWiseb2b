<?php

namespace Wise\Client\Service\Client\Helper\Interfaces;

use Wise\Core\Service\Interfaces\CommonHelperInterface;

interface ClientHelperInterface extends CommonHelperInterface
{
    public function getClientStatusIdIfExistsByData(array &$data): ?int;
}
