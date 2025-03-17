<?php

namespace Wise\GPSR\ApiAdmin\Controller\GpsrSupplier;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiAdmin\Controller\AbstractDeleteAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;
use Wise\GPSR\ApiAdmin\Service\GpsrSupplier\Interfaces\DeleteGpsrSupplierByKeyServiceInterface;

class DeleteGpsrSupplierByKeyController extends AbstractDeleteAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_DELETE,
        ScopeNames::CLIENTS_ALL,
        ScopeNames::CLIENTS_DELETE,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly DeleteGpsrSupplierByKeyServiceInterface $deleteSupplierByKeyService
    ) {
        parent::__construct($endpointShareMethodsHelper, $deleteSupplierByKeyService);
    }

    #[Route(
        path: '/{id}',
        requirements: [
            'id' => '([a-zA-Z0-9-_])+',
        ],
        methods: ['DELETE']
    )]
    #[OADelete(
        description: 'Usuwanie dostawcy',
        tags: ['Supplier'],
        parametersDto: new OA\JsonContent(ref: "#/components/schemas/CommonAdminApiDeleteParametersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
