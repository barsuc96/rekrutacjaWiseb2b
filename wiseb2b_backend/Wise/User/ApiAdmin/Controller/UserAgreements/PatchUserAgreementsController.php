<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Controller\UserAgreements;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\Controller\AdminApiBaseController;
use Wise\Core\Security\Constants\ScopeNames;
use Wise\User\ApiAdmin\Dto\UserAgreements\PutUserAgreementsDto;
use Wise\User\ApiAdmin\Service\UserAgreements\Interfaces\PutUserAgreementsServiceInterface;

class PatchUserAgreementsController extends AdminApiBaseController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_PATCH,
        ScopeNames::USER_AGREEMENTS_ALL,
        ScopeNames::USER_AGREEMENTS_PATCH,
    ];

    public function __construct(
        private readonly PutUserAgreementsServiceInterface $service
    ) {}

    #[Route(
        path: '',
        methods: ['PATCH'],
    )]
    #[OA\Patch(
        description: 'Modyfikacja listy umów użytkownika',
        tags: ['UserAgreements']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PutUserAgreementsDto", type: "object")
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
    public function patchUserAgreementsAction(Request $request): JsonResponse
    {
        return $this->service->process($request->headers->all(), $request->getContent(), PutUserAgreementsDto::class, true);
    }
}
