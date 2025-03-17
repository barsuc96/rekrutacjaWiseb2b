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
use Wise\User\ApiAdmin\Dto\UserAgreements\DeleteUserAgreementsByKeyAttributesDto;
use Wise\User\ApiAdmin\Service\UserAgreements\Interfaces\DeleteUserAgreementsByKeyServiceInterface;

class DeleteUserAgreementsByKeyController extends AdminApiBaseController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_DELETE,
        ScopeNames::USER_AGREEMENTS_ALL,
        ScopeNames::USER_AGREEMENTS_DELETE,
    ];

    public function __construct(
        private readonly DeleteUserAgreementsByKeyServiceInterface $deleteUserAgreementsService
    ) {
    }

    #[Route(
        path: '/{user_agreement_id}',
        requirements: [
            'user_agreement_id' => '([a-zA-Z0-9-_])+',
        ],
        methods: ['DELETE']
    )]
    #[OA\Tag(name: 'UserAgreements')]
    #[OA\Parameter(
        name: 'x-request-uuid',
        description: 'UUID requestu',
        in: 'header',
        schema: new OA\Schema(type: 'string'),
        example: '49c9aa13-c5c3-474b-a874-755f9d553779'
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Zwrotka w przypadku znalezionych i poprawienie usuniętych obiektów",
        content: new OA\JsonContent(ref: "#/components/schemas/CommonDeleteResponseAdminApiDto", type: "object")
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
    public function deleteUserAgreementsAction(Request $request): JsonResponse
    {
        return $this->deleteUserAgreementsService->process(
            $request->headers->all(),
            $request->attributes->all()['_route_params'],
            DeleteUserAgreementsByKeyAttributesDto::class
        );
    }
}
