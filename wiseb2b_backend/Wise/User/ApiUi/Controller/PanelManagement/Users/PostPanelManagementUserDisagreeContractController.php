<?php

namespace Wise\User\ApiUi\Controller\PanelManagement\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\PostPanelManagementUserDisagreeContractServiceInterface;

class PostPanelManagementUserDisagreeContractController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostPanelManagementUserDisagreeContractServiceInterface $postPanelManagementUserDisagreeContractService,
    ){
        parent::__construct($endpointShareMethodsHelper, $postPanelManagementUserDisagreeContractService);
    }

    #[Route(
        path: '/agreements/{userId}/disagree/{contractId}',
        requirements: ['userId' => '\d+', 'contractId' => '\d+'],
        methods: ['POST']
    )]
    #[OAPost(
        description: 'Endpoint do wypowiedzenia zgody do umowy poprzez panel administracyjny',
        tags: ['PanelUsers'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostPanelManagementUserDisagreeContractDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
