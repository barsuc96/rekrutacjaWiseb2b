<?php

namespace Wise\Client\ApiUi\Controller\PanelManagement;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementClientServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;

use OpenApi\Attributes as OA;
class PostPanelManagementClientController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostPanelManagementClientServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/', methods: Request::METHOD_POST)]
    #[OAPost(
        description: 'Dodanie klienta',
        tags: ['PanelClients'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostPanelManagementClientDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

