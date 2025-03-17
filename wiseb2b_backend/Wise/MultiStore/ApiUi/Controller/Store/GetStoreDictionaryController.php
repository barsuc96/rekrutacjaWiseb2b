<?php

declare(strict_types=1);

namespace Wise\MultiStore\ApiUi\Controller\Store;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\MultiStore\ApiUi\Dto\GetStoreDictionaryQueryParametersDto;
use Wise\MultiStore\ApiUi\Service\Interfaces\GetStoreDictionaryServiceInterface;

class GetStoreDictionaryController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetStoreDictionaryServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(path: '/dictionary', methods: Request::METHOD_GET)]
    #[CommonGetDtoParamAttributes(
        description: 'Zwraca słownik skonfigurowanych sklepów w formie dictionary (do panelu administracyjnego)',
        tags: ['MultiStore'],
        parametersDtoClass: GetStoreDictionaryQueryParametersDto::class
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/GetStoreDictionaryResponseDto", type: "object"
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getAction(Request $request): JsonResponse
    {
        return $this->service->process($request, GetStoreDictionaryQueryParametersDto::class);
    }
}
