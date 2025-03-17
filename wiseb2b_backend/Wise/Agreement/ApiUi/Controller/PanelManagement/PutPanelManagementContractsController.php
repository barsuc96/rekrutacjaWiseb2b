<?php

namespace Wise\Agreement\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\PutPanelManagementContractsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiUi\Controller\AbstractPutController;

class PutPanelManagementContractsController extends AbstractPutController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutPanelManagementContractsServiceInterface $putPanelManagementContractsService,
    ){
        parent::__construct($endpointShareMethodsHelper, $putPanelManagementContractsService);
    }

    #[Route(
        path: '/contracts/{id}',
        requirements: ['id' => '\d+'],
        methods: ['PUT']
    )]
    #[OAPut(
        description: 'Endpoint do aktualizacji nowej umowy do panelu zarządzania',
        tags: ['PanelAgreement'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutPanelManagementContracts", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
