<?php

declare(strict_types=1);

namespace Wise\Service\Domain\Service;

use Wise\Core\Repository\EntityWithTranslations;
use Wise\Core\Repository\RepositoryInterface;

interface ServiceRepositoryInterface extends RepositoryInterface, EntityWithTranslations
{
}
