<?php

namespace Wise\GPSR\ApiAdmin\Controller\GpsrSupplier;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiAdmin\Controller\AbstractGetListAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;
use Wise\GPSR\ApiAdmin\Service\GpsrSupplier\Interfaces\GetGpsrSupplierServiceInterface;

class GetGpsrSupplierController extends AbstractGetListAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PUT,
        ScopeNames::ORDERS_ALL,
        ScopeNames::ORDERS_PUT,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetGpsrSupplierServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '',
        methods: ['GET']
    )]
    #[OAGet(
        description: 'Zwraca listę dostawców',
        tags: ['Supplier'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetGpsrSuppliersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
