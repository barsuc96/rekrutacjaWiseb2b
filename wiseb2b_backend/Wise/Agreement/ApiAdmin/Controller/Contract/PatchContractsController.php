<?php

namespace Wise\Agreement\ApiAdmin\Controller\Contract;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiAdmin\Service\Contract\Interfaces\PutContractServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAPatch;
use Wise\Core\ApiAdmin\Controller\AbstractPatchAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class PatchContractsController extends AbstractPatchAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PATCH,
        ScopeNames::CLIENTS_ALL,
        ScopeNames::CLIENTS_PATCH,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutContractServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '',
        methods: ['PATCH'],
    )]
    #[OAPatch(
        description: 'Dodanie|Modyfikacja listy umów',
        tags: ['Contract'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutContractDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
