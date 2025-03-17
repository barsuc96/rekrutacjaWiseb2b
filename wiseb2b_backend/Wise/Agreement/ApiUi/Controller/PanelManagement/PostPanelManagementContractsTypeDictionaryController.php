<?php

namespace Wise\Agreement\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementContractsTypeDictionaryServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;

class PostPanelManagementContractsTypeDictionaryController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostPanelManagementContractsTypeDictionaryServiceInterface $postPanelManagementContractsService,
    ){
        parent::__construct($endpointShareMethodsHelper, $postPanelManagementContractsService);
    }

    #[Route(
        path: '/contract-types-dictionary',
        methods: ['POST']
    )]
    #[OAPost(
        description: 'Endpoint do dodawania nowego typu wykorzystywanego do konfiguracji umów w panelu zarządzania',
        tags: ['PanelAgreement'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostPanelManagementContractsTypeDictionaryDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
