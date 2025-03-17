<?php

declare(strict_types=1);

namespace Wise\GPSR\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetListController;
use Wise\GPSR\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementSuppliersInterface;

class GetPanelManagementSuppliersController extends AbstractGetListController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetPanelManagementSuppliersInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/suppliers', methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca listę wszystkich odbiorców dla panelu administracyjnego',
        tags: ['PanelSupplier'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementSuppliersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

