<?php

namespace Wise\User\ApiUi\Controller\PanelManagement\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\PostPanelManagementUserServiceInterface;

class PostPanelManagementUserController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostPanelManagementUserServiceInterface $postPanelManagementUserService,
    ){
        parent::__construct($endpointShareMethodsHelper, $postPanelManagementUserService);
    }

    #[Route(
        path: '/',
        methods: ['POST']
    )]
    #[OAPost(
        description: 'Endpoint do dodawania nowego użytkownika poprzez panel administracyjny',
        tags: ['PanelUsers'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostPanelManagementUserDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
