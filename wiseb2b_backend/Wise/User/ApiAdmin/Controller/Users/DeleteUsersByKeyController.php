<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Controller\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\Controller\AdminApiBaseController;
use Wise\Core\Security\Constants\ScopeNames;
use Wise\User\ApiAdmin\Dto\Users\DeleteUsersByKeyAttributesDto;
use Wise\User\ApiAdmin\Service\Users\Interfaces\DeleteUsersByKeyServiceInterface;

class DeleteUsersByKeyController extends AdminApiBaseController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_DELETE,
        ScopeNames::USERS_ALL,
        ScopeNames::USERS_DELETE,
    ];

    public function __construct(
        private readonly DeleteUsersByKeyServiceInterface $deleteUsersService
    ) {
    }

    #[Route(
        path: '/{user_id}',
        requirements: [
            'user_id' => '([a-zA-Z0-9-_])+',
        ],
        methods: ['DELETE']
    )]
    #[OA\Tag(name: 'Users')]
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
    public function deleteUsersAction(Request $request): JsonResponse
    {
        return $this->deleteUsersService->process(
            $request->headers->all(),
            $request->attributes->all()['_route_params'],
            DeleteUsersByKeyAttributesDto::class
        );
    }
}
