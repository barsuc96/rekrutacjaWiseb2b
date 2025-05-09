<?php

namespace Wise\Agreement\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementContractsTypesSystemDictionaryServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetListController;

class GetPanelManagementContractsTypesSystemDictionaryController extends AbstractGetListController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetPanelManagementContractsTypesSystemDictionaryServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }
    #[Route(path: '/contracts-types-system-dictionary', methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca listę typów systemów wykorzystywanych do konfiguracji umów w panelu zarządzania',
        tags: ['PanelAgreement'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementContractsTypesSystemDictionary", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
