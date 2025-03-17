<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Controller\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\User\ApiUi\Dto\Users\PostUserRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PostUserServiceInterface;

#[AsController]
class PostUserController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly PostUserServiceInterface $service,
    ) {
        parent::__construct($security);
    }


    #[Route(path: '/', methods: Request::METHOD_POST)]
    #[OA\Tag(name: 'Users')]
    #[OA\RequestBody(
        description: 'Utworzenie użytkownika. Będzie zaimplementowane na liście użytkowników w dashboardzie',
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PostUserRequestDto", type: "object")
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie zapisano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/Common200FormResponseDto",
            type: "object"
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpiły błędy",
        content: new OA\JsonContent(
            ref: "#/components/schemas/Common422FormResponseDto",
            type: "object"
        ),
    )]
    public function postUsersAction(Request $request): JsonResponse
    {
        return $this->service->process($request->getContent(), PostUserRequestDto::class);
    }
}
