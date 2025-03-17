<?php

namespace Wise\GPSR\ApiUi\Controller\PanelManagement;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;

use OpenApi\Attributes as OA;
use Wise\GPSR\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementSupplierServiceInterface;

class PostPanelManagementSupplierController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostPanelManagementSupplierServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/supplier', methods: Request::METHOD_POST)]
    #[OAPost(
        description: 'Dodanie odbiorcy',
        tags: ['PanelSupplier'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostPanelManagementSupplierDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

