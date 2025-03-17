<?php

namespace Wise\Client\ApiAdmin\Controller\ClientGroups;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiAdmin\Service\ClientGroups\Interfaces\PutClientGroupsServiceInterface;
use Wise\Client\ApiAdmin\Service\Clients\Interfaces\PutClientsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAPatch;
use Wise\Core\ApiAdmin\Controller\AbstractPatchAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class PatchClientGroupsController extends AbstractPatchAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PATCH,
        ScopeNames::CLIENTS_ALL,
        ScopeNames::CLIENTS_PATCH,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutClientGroupsServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '',
        methods: ['PATCH'],
    )]
    #[OAPatch(
        description: 'Dodanie|Modyfikacja listy grup klientów',
        tags: ['ClientGroups'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutClientGroupsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
