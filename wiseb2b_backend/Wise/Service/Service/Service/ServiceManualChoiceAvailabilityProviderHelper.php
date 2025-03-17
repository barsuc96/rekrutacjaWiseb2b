<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Webmozart\Assert\Assert;
use Wise\Service\Domain\ServiceManualChoiceAvailabilityProviderInterface;
use Wise\Service\Service\Service\Interfaces\ServiceHelperInterface;
use Wise\Service\Service\Service\Interfaces\ServiceManualChoiceAvailabilityProviderHelperInterface;

class ServiceManualChoiceAvailabilityProviderHelper implements ServiceManualChoiceAvailabilityProviderHelperInterface
{
    /**
     * @var iterable<ServiceManualChoiceAvailabilityProviderInterface>
     */
    private iterable $providers;

    public function __construct(
        #[TaggedIterator('service_provider.manual_choice_availability')] iterable $providers,
        private readonly ServiceHelperInterface $serviceHelper,
    )
    {
        Assert::allIsInstanceOf($providers, ServiceManualChoiceAvailabilityProviderInterface::class);
        $this->providers = $providers;
    }

    public function getManualChoiceAvailabilityProviderForService(int $serviceId): ?ServiceManualChoiceAvailabilityProviderInterface
    {
        $providerName = $this->serviceHelper->getDriverNameByServiceId($serviceId);

        return $this->serviceHelper->getProviderFromProviders($providerName, $this->providers);
    }
}
