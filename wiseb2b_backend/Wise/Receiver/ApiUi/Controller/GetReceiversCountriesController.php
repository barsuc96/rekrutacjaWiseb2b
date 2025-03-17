<?php

namespace Wise\Receiver\ApiUi\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\Receiver\ApiUi\Dto\GetReceiversCountriesQueryParametersDto;
use Wise\Receiver\ApiUi\Service\Interfaces\GetReceiversCountriesServiceInterface;


/**
 * Endpoint do pobierania listy krajów do poprawnego zapisania adresu odbiorcy
 */
class GetReceiversCountriesController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetReceiversCountriesServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: '/countries',
        methods: ['GET']
    )]
    #[CommonGetDtoParamAttributes(
        description: 'Lista krajów do poprawnego zapisania adresu odbiorcy.',
        tags: ['Receivers'],
        parametersDtoClass: GetReceiversCountriesQueryParametersDto::class,
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(ref: "#/components/schemas/GetReceiversCountriesResponseDto", type: "object"),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getReceiversAction(Request $request): JsonResponse
    {
        return $this->service->process($request, GetReceiversCountriesQueryParametersDto::class);
    }
}
