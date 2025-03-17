<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiUi\Controller\AbstractPutController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\PutPanelManagementClientReceiverServiceInterface;


class PutPanelManagementClientReceiverController extends AbstractPutController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutPanelManagementClientReceiverServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/{clientId}/receivers/{id}', requirements: ['clientId' => '\d+', 'id' => '\d+'], methods: Request::METHOD_PUT)]
    #[OAPut(
        description: 'Umożliwia edycję odbiorcy klienta dla panelu administracyjnego',
        tags: ['PanelManagementClient'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutPanelManagementClientReceiverDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
    return parent::getAction($request);
    }
}

