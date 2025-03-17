<?php

declare(strict_types=1);

namespace Wise\Core\EventListener\Exception;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Webmozart\Assert\Assert;
use Wise\Core\Enum\ControllerScopeEnum;

/**
 * Pozwala obsłużyć wyjątki w zależności od scope'a za pomocą providerów
 * Obsługa wyjątków, które nie dziedziczą po CommonLogicException
 */
class ExceptionListenerAdditionalScopeProviderService
{
    protected const PROVIDER_INTERFACE = ExceptionListenerScopeProviderInterface::class;

    private iterable $providers;

    public function __construct(
        #[TaggedIterator('wise_exception_listener.scope_provider')]
        iterable $providers
    ) {
        Assert::allIsInstanceOf($providers, static::PROVIDER_INTERFACE);
        $this->providers = $providers;
    }

    public function handleExceptionByScope(string|ControllerScopeEnum $controllerScope, ExceptionEvent $event, \Throwable $exception, array $headers): void
    {
        if($controllerScope instanceof ControllerScopeEnum) {
            $controllerScope = $controllerScope->value;
        }

        foreach ($this->providers as $provider) {
            /** @var ExceptionListenerScopeProviderInterface $provider */
            if ($provider->support($controllerScope)) {
                $provider->handleExceptionByScope($event, $exception, $headers);
            }
        }
    }
}
