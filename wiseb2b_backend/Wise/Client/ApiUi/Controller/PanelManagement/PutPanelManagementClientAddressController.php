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
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\PutPanelManagementClientAddressServiceInterface;


class PutPanelManagementClientAddressController extends AbstractPutController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutPanelManagementClientAddressServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/{id}/address', requirements: ['id' => '\d+'], methods: Request::METHOD_PUT)]
    #[OAPut(
        description: 'Możliwość aktualizacji adresu klienta dla panelu administracyjnego',
        tags: ['PanelClients'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutPanelManagementClientAddressDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

