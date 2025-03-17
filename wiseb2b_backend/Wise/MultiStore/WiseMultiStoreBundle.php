<?php

declare(strict_types=1);

namespace Wise\MultiStore;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WiseMultiStoreBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new WiseMultiStoreExtension();
        }
        return $this->extension;
    }
}
