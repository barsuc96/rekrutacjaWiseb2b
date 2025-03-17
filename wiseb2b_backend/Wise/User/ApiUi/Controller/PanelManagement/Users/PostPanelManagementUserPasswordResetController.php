<?php

namespace Wise\User\ApiUi\Controller\PanelManagement\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\PostPanelManagementUserPasswordResetServiceInterface;

/**
 * Endpoint do resetowania hasła w panelu użytkownika
 */
class PostPanelManagementUserPasswordResetController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostPanelManagementUserPasswordResetServiceInterface $postPanelManagementUserPasswordResetService,
    ){
        parent::__construct($endpointShareMethodsHelper, $postPanelManagementUserPasswordResetService);
    }

    #[Route(
        path: '/reset-password/{id}',
        requirements: ['id' => '\d+'],
        methods: ['POST']
    )]
    #[OAPost(
        description: 'Endpoint wysyłający link do resetowania hasła',
        tags: ['PanelUsers'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostPanelManagementUserPasswordResetDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
