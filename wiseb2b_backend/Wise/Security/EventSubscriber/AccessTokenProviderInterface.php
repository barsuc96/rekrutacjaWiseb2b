<?php

namespace Wise\Security\EventSubscriber;

use Symfony\Component\HttpKernel\Event\ControllerEvent;

interface AccessTokenProviderInterface
{
    public function support($currentController): bool;
    public function supportAdditionalAccessTokenProvider(ControllerEvent $event, $currentController): bool;
}
