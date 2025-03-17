<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Webmozart\Assert\Assert;
use Wise\Service\Domain\ServiceCostProviderInterface;
use Wise\Service\Service\Service\Interfaces\ServiceCostProviderHelperInterface;
use Wise\Service\Service\Service\Interfaces\ServiceHelperInterface;

class ServiceCostProviderHelper implements ServiceCostProviderHelperInterface
{
    /**
     * @var iterable<ServiceCostProviderInterface>
     */
    private iterable $providers;

    public function __construct(
        #[TaggedIterator('service_provider.cost')] iterable $providers,
        private readonly ServiceHelperInterface $serviceHelper,
    )
    {
        Assert::allIsInstanceOf($providers, ServiceCostProviderInterface::class);
        $this->providers = $providers;
    }

    public function getCostProviderForService(int $serviceId): ?ServiceCostProviderInterface
    {
        $providerName = $this->serviceHelper->getDriverNameByServiceId($serviceId);

        return $this->serviceHelper->getProviderFromProviders($providerName, $this->providers);
    }
}
