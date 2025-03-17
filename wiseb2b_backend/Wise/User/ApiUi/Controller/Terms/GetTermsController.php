<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Controller\Terms;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\User\ApiUi\Dto\GetTerms;
use Wise\User\ApiUi\Dto\GetTermsQueryParametersDto;
use Wise\User\ApiUi\Service\Interfaces\GetTermsServiceInterface;

class GetTermsController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        protected readonly GetTermsServiceInterface $service,
    ) {
        parent::__construct($security);
    }

    #[Route(path: '/terms', methods: Request::METHOD_GET)]
    #[CommonGetDtoParamAttributes(
        description: 'Dane regulaminu/ów. Strona regulamin w dashboardzie',
        tags: ['Terms'],
        parametersDtoClass: GetTerms::class
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(ref: "#/components/schemas/GetTermsResponseDto", type: "object"),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getAction(Request $request): JsonResponse
    {
        return $this->service->process($request, GetTermsQueryParametersDto::class);
    }
}
