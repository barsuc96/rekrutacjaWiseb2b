<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Controller\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiAdmin\Service\Clients\Interfaces\GetClientServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiAdmin\Controller\AbstractGetDetailsAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class GetClientController extends AbstractGetDetailsAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PUT,
        ScopeNames::ORDERS_ALL,
        ScopeNames::ORDERS_PUT,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetClientServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '/{id}',
        requirements: ['id' => '\d+'],
        methods: ['GET']
    )]
    #[OAGet(
        description: 'Zwraca szczegóły klienta',
        tags: ['Clients'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetClientDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
