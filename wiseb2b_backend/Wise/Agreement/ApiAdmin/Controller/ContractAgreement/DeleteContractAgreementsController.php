<?php

namespace Wise\Agreement\ApiAdmin\Controller\ContractAgreement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiAdmin\Service\Contract\Interfaces\DeleteContractServiceInterface;
use Wise\Agreement\ApiAdmin\Service\ContractAgreement\Interfaces\DeleteContractAgreementsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiAdmin\Controller\AbstractDeleteAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class DeleteContractAgreementsController extends AbstractDeleteAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_DELETE,
        ScopeNames::CLIENTS_ALL,
        ScopeNames::CLIENTS_DELETE,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly DeleteContractAgreementsServiceInterface $deleteContractAgreementsService
    ) {
        parent::__construct($endpointShareMethodsHelper, $deleteContractAgreementsService);
    }

    #[Route(
        path: '/{id}',
        requirements: [
            'id' => '([a-zA-Z0-9-_])+',
        ],
        methods: ['DELETE']
    )]
    #[OADelete(
        description: 'Usuwanie zgody do umowy',
        tags: ['ContractAgreement'],
        parametersDto: new OA\JsonContent(ref: "#/components/schemas/CommonAdminApiDeleteParametersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
