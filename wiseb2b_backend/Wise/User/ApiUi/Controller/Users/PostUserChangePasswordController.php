<?php

namespace Wise\User\ApiUi\Controller\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonPostDtoParamAttributes;
use Wise\Core\Dto\Attribute\CommonPutDtoParamAttributes;
use Wise\User\ApiUi\Dto\Users\PutUserChangePasswordQueryParametersDto;
use Wise\User\ApiUi\Dto\Users\PostUserChangePasswordRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PostUserChangePasswordInterface;

class PostUserChangePasswordController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        protected readonly PostUserChangePasswordInterface $service,
    ) {
        parent::__construct($security);
    }

    #[
        Route(
            path: '/change-password',
            methods: Request::METHOD_POST
        ),
        CommonPostDtoParamAttributes(
            description: 'Aktualizacja hasła użytkownika',
            tags: ['Users'],
            parametersDtoClass: PutUserChangePasswordQueryParametersDto::class
        ),
        OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/PostUserChangePasswordRequestDto",
                type: "object"
            )
        ),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Poprawnie zapisano dane",
            content: new OA\JsonContent(
                ref: "#/components/schemas/Common200FormResponseDto",
                type: "object"
            ),
        ),
        OA\Response(
            response: Response::HTTP_BAD_REQUEST,
            description: "Wystąpiły błędy",
            content: new OA\JsonContent(
                ref: "#/components/schemas/Common422FormResponseDto",
                type: "object"
            ),
        )
    ]
    public function postAction(Request $request): JsonResponse
    {
        return $this->service->process($request->getContent(), PostUserChangePasswordRequestDto::class);
    }
}
