<?php

namespace Wise\Agreement\ApiAdmin\Controller\ContractAgreement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiAdmin\Service\Contract\Interfaces\GetContractsServiceInterface;
use Wise\Agreement\ApiAdmin\Service\ContractAgreement\Interfaces\GetContractAgreementServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiAdmin\Controller\AbstractGetListAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class GetContractAgreementsController extends AbstractGetListAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PUT,
        ScopeNames::ORDERS_ALL,
        ScopeNames::ORDERS_PUT,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetContractAgreementServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '',
        methods: ['GET']
    )]
    #[OAGet(
        description: 'Zwraca listę zgód',
        tags: ['ContractAgreement'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetContractsAgreementsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
