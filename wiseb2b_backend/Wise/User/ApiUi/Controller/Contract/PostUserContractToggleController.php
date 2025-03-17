<?php

namespace Wise\User\ApiUi\Controller\Contract;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;
use Wise\User\ApiUi\Service\Contract\Interfaces\PostUserContractToggleServiceInterface;

class PostUserContractToggleController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostUserContractToggleServiceInterface $contractToggleService,
    ){
        parent::__construct($endpointShareMethodsHelper, $contractToggleService);
    }

    #[Route(
        path: '/toggle',
        methods: ['POST']
    )]
    #[OAPost(
        description: 'Komenda odpowiedzialna za zapisanie zgody/odmowę użytkownika na umowę (wiele zgód/odmów jednocześnie) w panelu klienta',
        tags: ['Users'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostUserContractsTogglesDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

