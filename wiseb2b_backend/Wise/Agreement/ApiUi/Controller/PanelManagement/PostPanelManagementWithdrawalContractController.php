<?php

namespace Wise\Agreement\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiUi\Service\PanelManagement\Interfaces\PostPanelManagementWithdrawalContractServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;

class PostPanelManagementWithdrawalContractController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostPanelManagementWithdrawalContractServiceInterface $postWithdrawalContractService,
    ){
        parent::__construct($endpointShareMethodsHelper, $postWithdrawalContractService);
    }

    #[Route(
        path: '/contracts/withdrawal/{contractId}',
        requirements: ['contractId' => '\d+'],
        methods: ['POST']
    )]
    #[OAPost(
        description: 'Komenda odpowiedzialna za wycofanie umowy w panelu administracyjnym (wymaga potwierdzenia)',
        tags: ['PanelAgreement'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostPanelManagementWithdrawalContractDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
