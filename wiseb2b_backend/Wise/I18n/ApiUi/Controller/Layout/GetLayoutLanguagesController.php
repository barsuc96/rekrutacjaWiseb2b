<?php

declare(strict_types=1);

namespace Wise\I18n\ApiUi\Controller\Layout;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\I18n\ApiUi\Dto\Layout\GetLanguagesParametersDto;
use Wise\I18n\ApiUi\Service\Layout\Interfaces\GetLayoutLanguagesServiceInterface;

class GetLayoutLanguagesController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        protected readonly GetLayoutLanguagesServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: '/languages',
        methods: [Request::METHOD_GET]
    )]
    #[CommonGetDtoParamAttributes(
        description: 'Lista dostępnych języków w systemie',
        tags: ['Layout'],
        parametersDtoClass: GetLanguagesParametersDto::class
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/GetLayoutLanguagesResponseDto",
            type: "object"
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getLayoutCategoriesAction(Request $request): JsonResponse
    {
        return $this->service->process($request, GetLanguagesParametersDto::class);
    }
}
