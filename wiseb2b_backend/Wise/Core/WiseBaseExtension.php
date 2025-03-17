<?php

declare(strict_types=1);

namespace Wise\Core;

use BadMethodCallException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class WiseBaseExtension extends Extension
{
    public const ALIAS = 'wise_base';

    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $configuration = $this->getConfiguration($configs, $containerBuilder);

        $config = $this->processConfiguration($configuration, $configs);

        $containerBuilder->setParameter($this->getAlias(), $config);
    }

    public function getAlias(): string
    {
        return static::getExtensionAlias();
    }

    public static function getExtensionAlias(): string
    {
        if (static::ALIAS == 'wise_base') {
            throw new BadMethodCallException("You need to overwrite ALIAS for your bundle extension");
        }

        return static::ALIAS;
    }
}