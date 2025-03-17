<?php

namespace Wise\Agreement\ApiAdmin\Controller\ContractAgreement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiAdmin\Service\ContractAgreement\Interfaces\PutContractAgreementsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiAdmin\Controller\AbstractPutAdminApiController;
use Wise\Core\Security\Constants\ScopeNames;

class PutContractAgreementController extends AbstractPutAdminApiController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PUT,
        ScopeNames::CLIENTS_ALL,
        ScopeNames::CLIENTS_PUT,
    ];

    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutContractAgreementsServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '/{id}',
        methods: ['PUT'],
    )]
    #[OAPut(
        description: 'Dodanie|Modyfikacja pojedyńczej zgody do umowy',
        tags: ['ContractAgreement'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutContractAgreementDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
