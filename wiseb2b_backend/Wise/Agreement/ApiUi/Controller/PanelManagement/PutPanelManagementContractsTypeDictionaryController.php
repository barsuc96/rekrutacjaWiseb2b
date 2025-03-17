<?php

namespace Wise\Agreement\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\PutPanelManagementContractsTypeDictionaryServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiUi\Controller\AbstractPutController;

class PutPanelManagementContractsTypeDictionaryController extends AbstractPutController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutPanelManagementContractsTypeDictionaryServiceInterface $putPanelManagementContractsService,
    ){
        parent::__construct($endpointShareMethodsHelper, $putPanelManagementContractsService);
    }

    #[Route(
        path: '/contract-types-dictionary/{id}',
        requirements: ['id' => '\d+'],
        methods: ['PUT']
    )]
    #[OAPut(
        description: 'Endpoint do aktualizacji nowego typu wykorzystywanego do konfiguracji umów w panelu zarządzania',
        tags: ['PanelAgreement'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutPanelManagementContractsTypeDictionaryDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
