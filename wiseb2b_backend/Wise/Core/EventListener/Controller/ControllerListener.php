<?php

declare(strict_types=1);

namespace Wise\Core\EventListener\Controller;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Wise\Core\ApiAdmin\Controller\AbstractAdminApiController;
use Wise\Core\ApiAdmin\Controller\AdminApiBaseController;
use Wise\Core\ApiUi\Controller\Core\AbstractUiApiController;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Enum\ControllerScopeEnum;
use Wise\Core\EventListener\Provider\ControllerListenerAdditionalScopeProviderService;
use Wise\Core\Helper\CommonApiShareMethodsHelper;

/**
 * Klasa nasłuchująca ustawianie kontrolera w core symfony, która ustawia scope na podstawie
 * ustawienie w AbstractController
 */
class ControllerListener
{
    public function __construct(
        private readonly ControllerListenerAdditionalScopeProviderService $additionalScopeProvider
    ){}

    public function onKernelController(ControllerEvent $event): void
    {
        $controllerCallable = $event->getController();
        $controller = null;

        if (is_array($controllerCallable) && sizeof($controllerCallable) > 0)
        {
            $controller = $controllerCallable[0];
        }

        /**
         * if there is no controller set scope to unknown
         */
        if ($controller == null)
        {
            $event->getRequest()->attributes->set(
                CommonApiShareMethodsHelper::CONTROLLER_SCOPE_ATTRIBUTE,
                ControllerScopeEnum::UNKNOWN
            );
            return;
        }

        /**
         * set scope to UiAPI
         */
        if (is_subclass_of($controller, UiApiBaseController::class))
        {
            $event->getRequest()->attributes->set(
                CommonApiShareMethodsHelper::CONTROLLER_SCOPE_ATTRIBUTE,
                ControllerScopeEnum::UI_API
            );
            return;
        }



        /**
         * set scope to UiAPI
         */
        if (is_subclass_of($controller, AbstractUiApiController::class))
        {
            $event->getRequest()->attributes->set(
                CommonApiShareMethodsHelper::CONTROLLER_SCOPE_ATTRIBUTE,
                ControllerScopeEnum::UI_API
            );
            return;
        }

        /**
         * set scope to AdminAPI
         */
        if (is_subclass_of($controller, AdminApiBaseController::class))
        {
            $event->getRequest()->attributes->set(
                CommonApiShareMethodsHelper::CONTROLLER_SCOPE_ATTRIBUTE,
                ControllerScopeEnum::ADMIN_API
            );
            return;
        }

        /**
         * set scope to AdminAPI
         */
        if (is_subclass_of($controller, AbstractAdminApiController::class))
        {
            $event->getRequest()->attributes->set(
                CommonApiShareMethodsHelper::CONTROLLER_SCOPE_ATTRIBUTE,
                ControllerScopeEnum::ADMIN_API
            );
            return;
        }

        /**
         * set scope from Provider
         */
        if($this->additionalScopeProvider->addInformationAboutNewScope($controller, $event)){
            return;
        }

        /**
         * set default scope to unknown
         */
        $event->getRequest()->attributes->set(
            CommonApiShareMethodsHelper::CONTROLLER_SCOPE_ATTRIBUTE,
            ControllerScopeEnum::UNKNOWN
        );
    }
}
