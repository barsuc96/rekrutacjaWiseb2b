<?php

declare(strict_types=1);

namespace Wise\Security\EventSubscriber\AccessToken;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Wise\Core\Controller\CommonBaseController;
use Wise\Payment\ApiAdmin\Controller\OnlinePayment\PostAuthorizeOnlinePaymentsAutopayController;
use Wise\Security\EventSubscriber\AbstractAccessTokenSubscriber;
use Wise\Security\EventSubscriber\AccessTokenProviderInterface;

/**
 *  Provider obsługujący kontrolery dziedziczące po CommonBaseController w AccessToken
 *  AccessToken - Obsługa autoryzacji
 */
#[AutoconfigureTag(name: 'wise_security.access_token')]
class AccessTokenCommonBaseControllerProvider extends AbstractAccessTokenSubscriber implements AccessTokenProviderInterface
{
    protected array $skippedAuthorizationControllers = [
        PostAuthorizeOnlinePaymentsAutopayController::class,
    ];

    public function support($currentController): bool
    {
        return $currentController instanceof CommonBaseController;
    }

    public function supportAdditionalAccessTokenProvider(ControllerEvent $event, $currentController): bool
    {
        if (in_array($currentController::class, $this->skippedAuthorizationControllers)) {
            return true;
        }

        return false;
    }
}
