<?php

namespace Wise\Agreement\ApiUi\Controller\ContractAgreement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiUi\Service\ContractAgreement\Interfaces\PostUserAgreesContractServiceInterfaces;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;

class PostUserAgreesContractController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostUserAgreesContractServiceInterfaces $postClientAcceptService,
    ){
        parent::__construct($endpointShareMethodsHelper, $postClientAcceptService);
    }

    #[Route(
        path: '/agrees',
        requirements: ['contractId' => '\d+'],
        methods: ['POST']
    )]
    #[OAPost(
        description: 'Komenda odpowiedzialna za zapisanie zgody użytkownika na umowę (wiele zgód jednocześnie)',
        tags: ['ContractAgreement'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostUserAgreesContractsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

