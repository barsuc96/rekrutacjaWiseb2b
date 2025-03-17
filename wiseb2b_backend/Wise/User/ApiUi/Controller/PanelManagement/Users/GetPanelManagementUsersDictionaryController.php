<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Controller\PanelManagement\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetListController;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\Payment\ApiUi\Service\PanelManagement\Settlements\Interfaces\GetPanelManagementSettlementsInterface;
use Wise\User\ApiUi\Dto\PanelManagement\Users\GetPanelManagementUsersQueryParametersDto;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\GetPanelManagementUsersDictionaryServiceInterface;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\GetPanelManagementUsersServiceInterface;

class GetPanelManagementUsersDictionaryController extends AbstractGetListController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetPanelManagementUsersDictionaryServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }
    #[Route(path: '/dictionary', methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca listę słowników użytkownik (do pola typu list) do panelu zarządzania',
        tags: ['PanelUsers'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementUsersDictionaryDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
