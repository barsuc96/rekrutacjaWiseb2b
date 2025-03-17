<?php

namespace Wise\Client\ApiAdmin\Controller\ClientGroups;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiAdmin\Service\ClientGroups\Interfaces\PutClientGroupsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiAdmin\Controller\AbstractPutAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class PutClientGroupsController extends AbstractPutAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PUT,
        ScopeNames::CLIENTS_ALL,
        ScopeNames::CLIENTS_PUT,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutClientGroupsServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '/',
        methods: ['PUT'],
    )]
    #[OAPut(
        description: 'Dodanie|Modyfikacja listy grup klientów',
        tags: ['ClientGroups'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutClientGroupsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
