<?php

declare(strict_types=1);

namespace Wise\GPSR\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiUi\Controller\AbstractPutController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\PutPanelManagementClientReceiverServiceInterface;
use Wise\GPSR\ApiUi\Service\PanelManagement\Interfaces\PutPanelManagementSupplierServiceInterface;


class PutPanelManagementSupplierController extends AbstractPutController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutPanelManagementSupplierServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/supplier/{id}', requirements: ['id' => '\d+'], methods: Request::METHOD_PUT)]
    #[OAPut(
        description: 'Umożliwia edycję odbiorcy dla panelu administracyjnego',
        tags: ['PanelSupplier'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutPanelManagementSupplierDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
    return parent::getAction($request);
    }
}

