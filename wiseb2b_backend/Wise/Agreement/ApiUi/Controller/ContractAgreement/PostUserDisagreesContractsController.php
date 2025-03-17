<?php

namespace Wise\Agreement\ApiUi\Controller\ContractAgreement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiUi\Service\ContractAgreement\Interfaces\PostUserDisagreesContractsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;

class PostUserDisagreesContractsController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostUserDisagreesContractsServiceInterface $postClientAcceptService,
    ){
        parent::__construct($endpointShareMethodsHelper, $postClientAcceptService);
    }

    #[Route(
        path: '/disagrees',
        requirements: ['contractId' => '\d+'],
        methods: ['POST']
    )]
    #[OAPost(
        description: 'Komenda odpowiedzialna za zapisanie odmowy użytkownika na umowę (wielu odmów jednocześnie)',
        tags: ['ContractAgreement'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostUserDisagreesContractsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
