<?php

declare(strict_types=1);

namespace Wise\Core\Domain\ShareMethodHelper;

use Symfony\Contracts\Translation\TranslatorInterface;

class EntityDomainServiceShareMethodsHelper
{
    public function __construct(
        public readonly TranslatorInterface $translator,
    ){}
}
