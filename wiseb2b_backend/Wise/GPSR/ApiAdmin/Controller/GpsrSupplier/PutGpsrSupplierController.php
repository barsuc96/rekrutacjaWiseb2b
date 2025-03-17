<?php

namespace Wise\GPSR\ApiAdmin\Controller\GpsrSupplier;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiAdmin\Controller\AbstractPutAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;
use Wise\GPSR\ApiAdmin\Service\GpsrSupplier\Interfaces\PutGpsrSupplierServiceInterface;

class PutGpsrSupplierController extends AbstractPutAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PUT,
        ScopeNames::CLIENTS_ALL,
        ScopeNames::CLIENTS_PUT,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutGpsrSupplierServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '/',
        methods: ['PUT'],
    )]
    #[OAPut(
        description: 'Dodanie|Modyfikacja dostawcy',
        tags: ['Supplier'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutGpsrSuppliersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
