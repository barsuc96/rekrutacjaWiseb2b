<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\Security\ApiUi\Dto\GetOverloginUsersQueryParametersDto;
use Wise\Security\ApiUi\Service\Interfaces\GetOverloginUsersServiceInterface;

/**
 * Kontroler do pobrania listy użytkowników, na któych zalogowany użytkownik może się przelogować.
 */
class GetOverloginUsersController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetOverloginUsersServiceInterface $getOverloginUsersService,
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: 'auth/overlogin-users',
        methods: ['GET']
    )]
    #[CommonGetDtoParamAttributes(
        description: 'Lista użytkowników, na któych zalogowany użytkownik może się przelogować.',
        tags: ['Auth'],
        parametersDtoClass: GetOverloginUsersQueryParametersDto::class,
    )]
    #[OA\Tag(name: 'Auth')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(ref: "#/components/schemas/GetOverloginUsersResponseDto", type: "object"),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getOverloginUsersAction(Request $request): JsonResponse
    {
        return $this->getOverloginUsersService->process(
            $request,
            GetOverloginUsersQueryParametersDto::class
        );
    }
}
