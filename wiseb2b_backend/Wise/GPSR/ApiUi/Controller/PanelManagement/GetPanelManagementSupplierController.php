<?php

namespace Wise\GPSR\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementClientReceiverServiceInterface;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementClientServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetDetailsController;
use Wise\GPSR\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementSupplierServiceInterface;

class GetPanelManagementSupplierController extends AbstractGetDetailsController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetPanelManagementSupplierServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/supplier/{id}', requirements: ['id' => '\d+'], methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca szczegóły odbiorcy dla panelu administracyjnego',
        tags: ['PanelSupplier'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementSupplierDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}


