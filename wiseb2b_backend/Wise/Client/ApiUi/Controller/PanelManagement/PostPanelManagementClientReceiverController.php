<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementClientReceiverServiceInterface;


class PostPanelManagementClientReceiverController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostPanelManagementClientReceiverServiceInterface $service,
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/{clientId}/receivers', requirements: ['clientId' => '\d+'], methods: Request::METHOD_POST)]
    #[OAPost(
        description: 'Umożliwia dodać nowego odbiorcę klienta dla panelu administracyjnego',
        tags: ['PanelManagementClient'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostPanelManagementClientReceiverDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

