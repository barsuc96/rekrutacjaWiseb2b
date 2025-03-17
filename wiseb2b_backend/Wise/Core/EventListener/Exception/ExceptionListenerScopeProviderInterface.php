<?php

namespace Wise\Core\EventListener\Exception;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

interface ExceptionListenerScopeProviderInterface
{
    public function support(string $controllerScope): bool;
    public function handleExceptionByScope(ExceptionEvent $event, \Throwable $exception, array $headers): void;
}
