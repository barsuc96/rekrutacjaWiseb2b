<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Controller\Agreements;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\Controller\AdminApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\Core\Security\Constants\ScopeNames;
use Wise\User\ApiAdmin\Dto\Agreements\GetAgreementsQueryParametersDto;
use Wise\User\ApiAdmin\Service\Agreements\Interfaces\GetAgreementsServiceInterface;

class GetAgreementsController extends AdminApiBaseController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_GET,
        ScopeNames::AGREEMENTS_ALL,
        ScopeNames::AGREEMENTS_GET,
    ];

    public function __construct(
        private readonly GetAgreementsServiceInterface $service
    ) {}

    #[Route(
        path: '',
        methods: ['GET']
    )]
    #[CommonGetDtoParamAttributes(
        description: self::class,
        tags: ['Agreements'],
        parametersDtoClass: GetAgreementsQueryParametersDto::class
    )]
    #[OA\Parameter(
        name: 'x-request-uuid',
        description: 'UUID requestu',
        in: 'header',
        schema: new OA\Schema(type: 'string'),
        example: '49c9aa13-c5c3-474b-a874-755f9d553779'
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Lista zgód',
        content: new OA\JsonContent(
            ref: "#/components/schemas/GetAgreementsResponseDto",
            type: "object"
        )
    )]
    #[OA\Response(
        response: Response::HTTP_UNAUTHORIZED,
        description: 'Błędny token autoryzacyjny',
        content: new OA\JsonContent(ref: "#/components/schemas/UnauthorizedResponseDto", type: "object")
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Niepoprawne dane wejściowe',
        content: new OA\JsonContent(ref: "#/components/schemas/InvalidInputDataResponseDto", type: "object")
    )]
    public function getAgreementsAction(Request $request): JsonResponse
    {
        return $this->service->process(
            $request->query,
            $request->headers->all(),
            GetAgreementsQueryParametersDto::class
        );
    }
}
