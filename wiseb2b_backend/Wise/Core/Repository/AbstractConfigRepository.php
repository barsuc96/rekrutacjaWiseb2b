<?php

declare(strict_types=1);

namespace Wise\Core\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\Core\WiseBaseExtension;

abstract class AbstractConfigRepository extends AbstractArrayRepository
{
    protected const ENTITY_CLASS = '';
    protected const EXTENSION = WiseBaseExtension::class;

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly ConfigServiceInterface|ContainerBagInterface $configService,
        ManagerRegistry $registry,
        ?string $entity = null
    ){
        parent::__construct($registry, $entity);
    }

    protected function getConfigData(): array
    {
        $extension = static::EXTENSION;
        return $this->configService->get($extension::getExtensionAlias());
    }
}
