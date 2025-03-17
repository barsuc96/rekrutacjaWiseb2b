<?php

namespace Wise\Receiver\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiUi\Controller\AbstractDeleteController;
use Wise\Receiver\ApiUi\Service\PanelManagement\Interfaces\DeletePanelManagementReceiverServiceInterface;

class DeletePanelManagementReceiverController extends AbstractDeleteController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly DeletePanelManagementReceiverServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: Request::METHOD_DELETE)]
    #[OADelete(
        description: 'Endpoint umożliwiający usunięcie odbiorcy',
        tags: ['PanelPromotions'],
        parametersDto: new OA\JsonContent(ref: "#/components/schemas/CommonUiApiDeleteParametersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
