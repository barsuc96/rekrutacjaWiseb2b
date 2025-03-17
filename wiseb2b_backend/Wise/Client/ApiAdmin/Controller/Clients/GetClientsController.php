<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Controller\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiAdmin\Service\Clients\Interfaces\GetClientsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiAdmin\Controller\AbstractGetListAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class GetClientsController extends AbstractGetListAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PUT,
        ScopeNames::ORDERS_ALL,
        ScopeNames::ORDERS_PUT,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetClientsServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '',
        methods: ['GET']
    )]
    #[OAGet(
        description: 'Zwraca listę klientów',
        tags: ['Clients'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetClientsResponseDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
