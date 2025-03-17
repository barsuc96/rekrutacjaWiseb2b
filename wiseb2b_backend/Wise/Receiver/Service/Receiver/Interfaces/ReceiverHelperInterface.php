<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver\Interfaces;

use Wise\Core\Service\Interfaces\CommonHelperInterface;

interface ReceiverHelperInterface extends CommonHelperInterface
{
    public function validateCountryCode(?string $countryCode): void;

    public function prepareAddressDtoData(array $data): array;
}
