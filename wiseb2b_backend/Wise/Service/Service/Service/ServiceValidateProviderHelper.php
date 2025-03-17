<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Webmozart\Assert\Assert;
use Wise\Service\Domain\ServiceValidateProviderInterface;
use Wise\Service\Service\Service\Interfaces\ServiceHelperInterface;
use Wise\Service\Service\Service\Interfaces\ServiceValidateProviderHelperInterface;

class ServiceValidateProviderHelper implements ServiceValidateProviderHelperInterface
{
    /**
     * @var iterable<ServiceValidateProviderInterface>
     */
    private iterable $providers;

    public function __construct(
        #[TaggedIterator('service_provider.validate')] iterable $providers,
        private readonly ServiceHelperInterface $serviceHelper,
    )
    {
        Assert::allIsInstanceOf($providers, ServiceValidateProviderInterface::class);
        $this->providers = $providers;
    }

    public function getValidateProviderForService(int $serviceId): ?ServiceValidateProviderInterface
    {
        $providerName = $this->serviceHelper->getDriverNameByServiceId($serviceId);

        return $this->serviceHelper->getProviderFromProviders($providerName, $this->providers);
    }
}
