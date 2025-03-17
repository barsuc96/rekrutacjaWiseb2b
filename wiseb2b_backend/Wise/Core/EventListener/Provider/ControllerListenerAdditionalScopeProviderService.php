<?php

namespace Wise\Core\EventListener\Provider;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Webmozart\Assert\Assert;
use Wise\Core\Helper\CommonApiShareMethodsHelper;

/**
 *  Pozwala dodać nowy Scope do requesta kontrollera. Dzięki temu wiemy, z jakiego api pochodzi dany request
 *  Wymagane do dodania nowego API
 * /
 */
class ControllerListenerAdditionalScopeProviderService
{
    protected const PROVIDER_INTERFACE = ControllerListenerScopeProviderInterface::class;

    private iterable $providers;

    public function __construct(
        #[TaggedIterator('wise_controller_listener.scope_provider')]
        iterable $providers
    ) {
        Assert::allIsInstanceOf($providers, static::PROVIDER_INTERFACE);
        $this->providers = $providers;
    }

    public function addInformationAboutNewScope($controller, ControllerEvent $event): bool
    {
        $isFound = false;

        foreach ($this->providers as $provider) {
            /** @var ControllerScopeResult $scope */
            $scope = $provider->configureRequestApi();

            if (is_subclass_of($controller, $scope->getBaseControllerClass()))
            {
                $event->getRequest()->attributes->set(
                    CommonApiShareMethodsHelper::CONTROLLER_SCOPE_ATTRIBUTE,
                    $scope->getScope()
                );

                $isFound = true;
                break;
            }
        }

        return $isFound;
    }
}
