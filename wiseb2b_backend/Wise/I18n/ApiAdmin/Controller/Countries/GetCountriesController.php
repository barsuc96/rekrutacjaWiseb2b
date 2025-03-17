<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Controller\Countries;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\Controller\AdminApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\Core\Security\Constants\ScopeNames;
use Wise\I18n\ApiAdmin\Dto\Countries\GetCountriesQueryParametersDto;
use Wise\I18n\ApiAdmin\Service\Countries\Interfaces\GetCountriesServiceInterface;

class GetCountriesController extends AdminApiBaseController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_GET,
        ScopeNames::COUNTRIES_ALL,
        ScopeNames::COUNTRIES_GET,
    ];

    public function __construct(
        private readonly GetCountriesServiceInterface $service
    ) {}

    #[Route(
        path: '',
        methods: ['GET']
    )]
    #[CommonGetDtoParamAttributes(
        description: self::class,
        tags: ['Countries'],
        parametersDtoClass: GetCountriesQueryParametersDto::class
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
        description: 'Lista obiektów oraz dodatkowe informacje',
        content: new OA\JsonContent(ref: "#/components/schemas/GetCountriesResponseDto", type: "object")
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
    public function getCountriesAction(Request $request): JsonResponse
    {
        return $this->service->process(
            $request->query,
            $request->headers->all(),
            GetCountriesQueryParametersDto::class
        );
    }
}
