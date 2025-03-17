<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiUi\Controller\AbstractDeleteController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\DeletePanelManagementClientReceiverServiceInterface;


class DeletePanelManagementClientReceiverController extends AbstractDeleteController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly DeletePanelManagementClientReceiverServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/{clientId}/receivers/{id}', requirements: ['clientId' => '\d+', 'id' => '\d+'], methods: Request::METHOD_DELETE)]
    #[OADelete(
        description: 'Endpoint umożliwiający usunięcie odbiorcy klienta dla panelu administracyjnego',
        tags: ['PanelManagementClient'],
        parametersDto: new OA\JsonContent(ref: "#/components/schemas/DeletePanelManagementClientReceiverDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

