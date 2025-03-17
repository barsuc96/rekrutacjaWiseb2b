<?php

declare(strict_types=1);

namespace Wise\GPSR;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wise\Product\WiseProductExtension;

class WiseGPSRBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new WiseGPSRExtension();
        }

        return $this->extension;
    }
}
