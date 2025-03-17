<?php

namespace Wise\Client\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementClientServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetDetailsController;

class GetPanelManagementClientController extends AbstractGetDetailsController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetPanelManagementClientServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca szczegóły klienta',
        tags: ['PanelClients'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementClientDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

