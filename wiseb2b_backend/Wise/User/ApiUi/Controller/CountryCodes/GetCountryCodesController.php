<?php

namespace Wise\User\ApiUi\Controller\CountryCodes;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use OpenApi\Attributes as OA;
use Wise\User\ApiUi\Dto\Users\GetCountryCodesQueryParametersDto;
use Wise\User\ApiUi\Dto\Users\GetUsersQueryParametersDto;
use Wise\User\ApiUi\Service\Interfaces\GetCountryCodesServiceInterface;

class GetCountryCodesController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetCountryCodesServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(path: '/country-codes', methods: Request::METHOD_GET)]
    #[CommonGetDtoParamAttributes(
        description: 'Zwraca liste kodów krajów obsługiwanych w polu country',
        tags: ['Users'],
        parametersDtoClass: GetCountryCodesQueryParametersDto::class
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/GetCountryCodesDto", type: "object"
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