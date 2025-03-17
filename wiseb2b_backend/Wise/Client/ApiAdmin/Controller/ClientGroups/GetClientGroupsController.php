<?php

namespace Wise\Client\ApiAdmin\Controller\ClientGroups;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiAdmin\Service\ClientGroups\Interfaces\GetClientGroupsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiAdmin\Controller\AbstractGetListAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class GetClientGroupsController extends AbstractGetListAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PUT,
        ScopeNames::ORDERS_ALL,
        ScopeNames::ORDERS_PUT,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetClientGroupsServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '',
        methods: ['GET']
    )]
    #[OAGet(
        description: 'Zwraca listę grupy klientów',
        tags: ['ClientGroups'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetClientGroupsResponseDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
