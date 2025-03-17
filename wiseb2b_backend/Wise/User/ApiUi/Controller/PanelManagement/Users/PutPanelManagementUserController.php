<?php

namespace Wise\User\ApiUi\Controller\PanelManagement\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiUi\Controller\AbstractPutController;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\PutPanelManagementUserServiceInterface;

class PutPanelManagementUserController extends AbstractPutController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutPanelManagementUserServiceInterface $putPanelManagementUserService,
    ){
        parent::__construct($endpointShareMethodsHelper, $putPanelManagementUserService);
    }

    #[Route(
        path: '/{id}',
        requirements: ['id' => '\d+'],
        methods: ['PUT']
    )]
    #[OAPut(
        description: 'Endpoint do aktualizacji użytkownika w panelu zarządzania',
        tags: ['PanelUsers'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutPanelManagementUserDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
