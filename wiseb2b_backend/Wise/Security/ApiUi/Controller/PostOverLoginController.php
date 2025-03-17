<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Controller;

use JetBrains\PhpStorm\Pure;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\ApiUi\Dto\CommonQueryParametersDto;
use Wise\Core\Dto\Attribute\CommonPostDtoParamAttributes;
use Wise\Security\ApiUi\Dto\PostOverLoginDto;
use Wise\Security\ApiUi\Service\Interfaces\PostOverLoginServiceInterface;

class PostOverLoginController extends UiApiBaseController
{
    #[Pure]
    public function __construct(
        Security $security,
        private readonly PostOverLoginServiceInterface $service,
    ) {
        parent::__construct(
            $security,
        );
    }

    #[Route(
        path: 'auth/overlogin',
        methods: ['POST'],
    )]
    #[CommonPostDtoParamAttributes(
        description: 'Endpoint umożliwia przelogowanie się na innego użytkownika',
        tags: ['Auth'],
        parametersDtoClass: CommonQueryParametersDto::class
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PostOverLoginDto", type: "object")
    )]
    #[OA\Response(
        ref: "#/components/schemas/ResponseDto",
        response: Response::HTTP_OK
    )]
    public function postOverLoginAction(Request $request): JsonResponse
    {
        return $this->service->process($request->getContent(), PostOverLoginDto::class);
    }
}
