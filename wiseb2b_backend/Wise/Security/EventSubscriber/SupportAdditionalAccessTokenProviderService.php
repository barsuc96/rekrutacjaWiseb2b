<?php

namespace Wise\Security\EventSubscriber;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Webmozart\Assert\Assert;

class SupportAdditionalAccessTokenProviderService
{
    protected const PROVIDER_INTERFACE = AccessTokenProviderInterface::class;

    private iterable $providers;

    public function __construct(
        #[TaggedIterator('wise_security.access_token')]
        iterable $providers
    ) {
        Assert::allIsInstanceOf($providers, static::PROVIDER_INTERFACE);
        $this->providers = $providers;
    }

    public function supportAdditionalAccessTokenProvider(ControllerEvent $event, $currentController): void
    {
        foreach ($this->providers as $provider) {
            if($provider->support($currentController)){
                if($provider->supportAdditionalAccessTokenProvider($event, $currentController)){
                    break;
                }
            }
        }
    }
}
