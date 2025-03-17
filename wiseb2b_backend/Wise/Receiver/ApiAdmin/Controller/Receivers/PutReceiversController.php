<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiAdmin\Controller\Receivers;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\Controller\AdminApiBaseController;
use Wise\Core\Security\Constants\ScopeNames;
use Wise\Receiver\ApiAdmin\Service\Receivers\Interfaces\PutReceiversServiceInterface;
use Wise\Receiver\ApiAdmin\Dto\Receivers\PutReceiversDto;

class PutReceiversController extends AdminApiBaseController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PUT,
        ScopeNames::RECEIVERS_ALL,
        ScopeNames::RECEIVERS_PUT,
    ];

    public function __construct(
        private readonly PutReceiversServiceInterface $service
    ) {}

    #[Route(
        path: '',
        methods: ['PUT'],
    )]
    #[OA\Tag(name: 'Receivers')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PutReceiversDto", type: "object")
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
        description: "Zwrotka w przypadku poprawnie przetworzonych wszystkich danych",
        content: new OA\JsonContent(ref: "#/components/schemas/CommonPutResponseAdminApiDto", type: "object")
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
    public function putReceiversAction(Request $request): JsonResponse
    {
        return $this->service->process($request->headers->all(), $request->getContent(), PutReceiversDto::class);
    }
}
