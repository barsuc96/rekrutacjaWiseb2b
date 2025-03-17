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
use Wise\User\ApiUi\Dto\Users\GetUsersCountriesQueryParametersDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersCountriesServiceInterface;

/**
 * Endpoint zwraca słownik krajów do formularza
 */
class GetUsersCountriesController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetUsersCountriesServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: '/countries',
        methods: ['GET']
    )]
    #[CommonGetDtoParamAttributes(
        description: 'Lista krajów do poprawnego zapisania adresu odbiorcy.',
        tags: ['Users'],
        parametersDtoClass: GetUsersCountriesQueryParametersDto::class,
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(ref: "#/components/schemas/GetUsersCountriesResponseDto", type: "object"),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getAction(Request $request): JsonResponse
    {
        return $this->service->process($request, GetUsersCountriesQueryParametersDto::class);
    }
}
