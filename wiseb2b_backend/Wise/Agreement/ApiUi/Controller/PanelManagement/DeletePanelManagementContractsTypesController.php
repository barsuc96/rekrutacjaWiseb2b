<?php

namespace Wise\Agreement\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\DeletePanelManagementContractsTypesServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiUi\Controller\AbstractDeleteController;

class DeletePanelManagementContractsTypesController extends AbstractDeleteController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly DeletePanelManagementContractsTypesServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '/contracts-types-dictionary/{id}',
        requirements: ['id' => '\d+'],
        methods: Request::METHOD_DELETE
    )]
    #[OADelete(
        description: 'Endpoint umożliwiający usunięcie typu umowy',
        tags: ['PanelAgreement'],
        parametersDto: new OA\JsonContent(ref: "#/components/schemas/DeletePanelManagementContractsTypesDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
