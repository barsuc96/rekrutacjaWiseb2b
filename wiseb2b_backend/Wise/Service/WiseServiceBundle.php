<?php

declare(strict_types=1);

namespace Wise\Service;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WiseServiceBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new WiseServiceExtension();
        }
        return $this->extension;
    }
}
