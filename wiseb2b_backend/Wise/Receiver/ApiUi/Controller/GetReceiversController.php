<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\Receiver\ApiUi\Dto\GetReceiversQueryParametersDto;
use Wise\Receiver\ApiUi\Service\Interfaces\GetReceiversServiceInterface;

/**
 * Endpoint do pobierania listy odbiorców, dla klienta
 */
class GetReceiversController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetReceiversServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: '',
        methods: ['GET']
    )]
    #[CommonGetDtoParamAttributes(
        description: 'Lista odbiorców. Użyte na liście odbiorców w dashboardzie.',
        tags: ['Receivers'],
        parametersDtoClass: GetReceiversQueryParametersDto::class,
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(ref: "#/components/schemas/GetReceiversResponseDto", type: "object"),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getReceiversAction(Request $request): JsonResponse
    {
        return $this->service->process($request, GetReceiversQueryParametersDto::class);
    }
}
