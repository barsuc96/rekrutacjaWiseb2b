<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetDetailsController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementClientAddressServiceInterface;


class GetPanelManagementClientAddressController extends AbstractGetDetailsController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetPanelManagementClientAddressServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/{id}/address', requirements: ['id' => '\d+'], methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca adres klienta dla panelu administracyjnego',
        tags: ['PanelClients'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementClientAddressDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

