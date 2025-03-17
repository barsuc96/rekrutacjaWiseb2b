<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Controller\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiAdmin\Service\Clients\Interfaces\PutClientsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiAdmin\Controller\AbstractPutAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class PutClientsController extends AbstractPutAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PUT,
        ScopeNames::CLIENTS_ALL,
        ScopeNames::CLIENTS_PUT,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutClientsServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '/',
        methods: ['PUT'],
    )]
    #[OAPut(
        description: 'Dodanie|Modyfikacja listy klientów',
        tags: ['Clients'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutClientsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
