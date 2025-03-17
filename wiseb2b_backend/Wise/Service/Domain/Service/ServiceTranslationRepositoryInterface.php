<?php

declare(strict_types=1);

namespace Wise\Service\Domain\Service;

use Wise\Core\Repository\RepositoryInterface;

interface ServiceTranslationRepositoryInterface extends RepositoryInterface
{
    public function removeByServiceId(int $serviceId, bool $flush = false): void;
}
