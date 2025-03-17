<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Controller\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiAdmin\Service\Clients\Interfaces\DeleteClientsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiAdmin\Controller\AbstractDeleteAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class DeleteClientsByKeyController extends AbstractDeleteAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_DELETE,
        ScopeNames::CLIENTS_ALL,
        ScopeNames::CLIENTS_DELETE,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly DeleteClientsServiceInterface $deleteClientsService
    ) {
        parent::__construct($endpointShareMethodsHelper, $deleteClientsService);
    }

    #[Route(
        path: '/{id}',
        requirements: [
            'id' => '([a-zA-Z0-9-_])+',
        ],
        methods: ['DELETE']
    )]
    #[OADelete(
        description: 'Usuwanie klienta',
        tags: ['Clients'],
        parametersDto: new OA\JsonContent(ref: "#/components/schemas/CommonAdminApiDeleteParametersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
