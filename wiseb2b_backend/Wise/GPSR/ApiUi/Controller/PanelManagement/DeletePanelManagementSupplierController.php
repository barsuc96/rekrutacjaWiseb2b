<?php

namespace Wise\GPSR\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiUi\Controller\AbstractDeleteController;
use Wise\GPSR\ApiUi\Service\PanelManagement\Interfaces\DeletePanelManagementSupplierServiceInterface;

class DeletePanelManagementSupplierController extends AbstractDeleteController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly DeletePanelManagementSupplierServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/supplier/{id}', requirements: ['id' => '\d+'], methods: Request::METHOD_DELETE)]
    #[OADelete(
        description: 'Endpoint umożliwiający usunięcie dostawcy',
        tags: ['PanelSupplier'],
        parametersDto: new OA\JsonContent(ref: "#/components/schemas/CommonUiApiDeleteParametersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

