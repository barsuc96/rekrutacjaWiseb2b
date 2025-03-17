<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Layout\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface ListLanguagesServiceInterface
{
    public function __invoke(): CommonServiceDTO;
}
