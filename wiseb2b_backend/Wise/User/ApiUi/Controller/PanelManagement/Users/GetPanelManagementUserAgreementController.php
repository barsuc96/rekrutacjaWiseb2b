<?php

namespace Wise\User\ApiUi\Controller\PanelManagement\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetListController;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\GetPanelManagementUserAgreementServiceInterface;

class GetPanelManagementUserAgreementController extends AbstractGetListController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetPanelManagementUserAgreementServiceInterface $getPanelManagementUserAgreementService
    ) {
        parent::__construct($endpointShareMethodsHelper, $getPanelManagementUserAgreementService);
    }

    #[Route(path: '/agreements/{userId}', requirements: ['userId' => '\d+'], methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca listę umów i zgód użytkownika. Wykorzystywane w panelu administracyjnym',
        tags: ['PanelUsers'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementUserAgreementsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
