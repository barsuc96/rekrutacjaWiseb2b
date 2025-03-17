<?php

namespace Wise\Core\ApiUi\Controller\Endpoint\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetListController;
use Wise\Core\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementReplicationRequestsServiceInterface;

class GetPanelManagementReplicationRequestsController extends AbstractGetListController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetPanelManagementReplicationRequestsServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }
    #[Route(path: '/replication-requests', methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca listę logów requestów do panelu zarządzania',
        tags: ['PanelLog'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementReplicationRequestsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
