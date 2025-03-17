<?php

namespace Wise\Core\EventListener\Provider;

interface ControllerListenerScopeProviderInterface
{
    public function configureRequestApi(): ControllerScopeResult;
}
