<?php

namespace Wise\User\ApiUi\Controller\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\User\ApiUi\Dto\Users\GetUsersTradersParametersDto;
use Wise\User\ApiUi\Dto\Users\GetUsersTradersQueryParametersDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersTraderServiceInterface;

class GetUsersTraderController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        protected readonly GetUsersTraderServiceInterface $service,
    ) {
        parent::__construct($security);
    }

    #[Route(path: '/trader', methods: Request::METHOD_GET)]
    #[CommonGetDtoParamAttributes(
        description: 'Zwraca informacje o opiekunie klienta',
        tags: ['Users'],
        parametersDtoClass: GetUsersTradersParametersDto::class
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/GetUsersTradersResponseDto", type: "object"
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getAction(Request $request): JsonResponse
    {
        return $this->service->process($request, GetUsersTradersQueryParametersDto::class);
    }
}