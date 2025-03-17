<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Controller\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\User\ApiUi\Dto\Users\GetUsersQueryParametersDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersServiceInterface;

class GetUsersController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetUsersServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(path: '/', methods: Request::METHOD_GET)]
    #[CommonGetDtoParamAttributes(
        description: 'Lista użytkowników. Użyte na stronie listy użytkowników w dashbordzie',
        tags: ['Users'],
        parametersDtoClass: GetUsersQueryParametersDto::class
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/GetUsersResponseDto", type: "object"
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getUsersAction(Request $request): JsonResponse
    {
        return $this->service->process($request, GetUsersQueryParametersDto::class);
    }
}
