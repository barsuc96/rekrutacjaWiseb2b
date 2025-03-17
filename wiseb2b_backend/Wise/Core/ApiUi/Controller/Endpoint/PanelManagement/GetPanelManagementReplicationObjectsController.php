<?php

namespace Wise\Core\ApiUi\Controller\Endpoint\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetListController;
use Wise\Core\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementReplicationObjectsServiceInterface;

class GetPanelManagementReplicationObjectsController extends AbstractGetListController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetPanelManagementReplicationObjectsServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }
    #[Route(path: '/replication-objects', methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca listę logów całych obiektów do panelu zarządzania',
        tags: ['PanelLog'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementReplicationObjectsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
