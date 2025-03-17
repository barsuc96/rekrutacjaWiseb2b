<?php

namespace Wise\Agreement\ApiAdmin\Controller\Contract;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiAdmin\Service\Contract\Interfaces\DeleteContractServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiAdmin\Controller\AbstractDeleteAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class DeleteContractByKeyController extends AbstractDeleteAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_DELETE,
        ScopeNames::CLIENTS_ALL,
        ScopeNames::CLIENTS_DELETE,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly DeleteContractServiceInterface $deleteContractService
    ) {
        parent::__construct($endpointShareMethodsHelper, $deleteContractService);
    }

    #[Route(
        path: '/{id}',
        requirements: [
            'id' => '([a-zA-Z0-9-_])+',
        ],
        methods: ['DELETE']
    )]
    #[OADelete(
        description: 'Usuwanie umowy',
        tags: ['Contract'],
        parametersDto: new OA\JsonContent(ref: "#/components/schemas/CommonAdminApiDeleteParametersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
