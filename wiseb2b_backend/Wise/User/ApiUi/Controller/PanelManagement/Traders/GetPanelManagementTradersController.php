<?php

namespace Wise\User\ApiUi\Controller\PanelManagement\Traders;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetDetailsController;
use Wise\User\ApiUi\Service\PanelManagement\Traders\Interfaces\GetPanelManagementTradersServiceInterface;

class GetPanelManagementTradersController extends AbstractGetDetailsController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetPanelManagementTradersServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }
    #[Route(path: '/', methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca liste handlowców do panelu zarządzania',
        tags: ['PanelUsers'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementTradersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
