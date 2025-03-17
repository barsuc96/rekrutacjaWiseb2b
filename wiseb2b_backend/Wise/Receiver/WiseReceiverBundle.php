<?php

declare(strict_types=1);

namespace Wise\Receiver;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WiseReceiverBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new WiseReceiverExtension();
        }
        return $this->extension;
    }
}
