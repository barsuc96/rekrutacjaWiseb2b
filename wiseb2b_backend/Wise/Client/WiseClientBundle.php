<?php

declare(strict_types=1);

namespace Wise\Client;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WiseClientBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new WiseClientExtension();
        }
        return $this->extension;
    }
}
