<?php

namespace Wise\Agreement\ApiUi\Controller\ContractAgreement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiUi\Service\ContractAgreement\Interfaces\PostUserContractToggleServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;

/**
 * Komenda odpowiedzialna za zapisanie zgody użytkownika na umowę (front może wskazać wiele umów jednocześnie oraz oznaczyć czy zgoda jest wyrażona czy nie)
 */
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
        description: 'Komenda odpowiedzialna za zapisanie zgody/odmowę użytkownika na umowę (wiele zgód/odmów jednocześnie)',
        tags: ['ContractAgreement'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostUserContractsToggleDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

