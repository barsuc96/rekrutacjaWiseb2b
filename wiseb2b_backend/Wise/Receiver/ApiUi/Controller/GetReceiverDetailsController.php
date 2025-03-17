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
use Wise\Receiver\ApiUi\Dto\GetReceiverDetailsQueryParametersDto;
use Wise\Receiver\ApiUi\Dto\GetReceiversQueryParametersDto;
use Wise\Receiver\ApiUi\Service\Interfaces\GetReceiverDetailsServiceInterface;
use Wise\Receiver\ApiUi\Service\Interfaces\GetReceiversServiceInterface;

class GetReceiverDetailsController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetReceiverDetailsServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: '/{receiverId}',
        requirements: ['receiverId' => '\d+'],
        methods: ['GET']
    )]
    #[CommonGetDtoParamAttributes(
        description: 'Szczegóły odbiorcy',
        tags: ['Receivers'],
        parametersDtoClass: GetReceiverDetailsQueryParametersDto::class,
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(ref: "#/components/schemas/GetReceiverResponseDto", type: "object"),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getReceiversAction(Request $request): JsonResponse
    {
        return $this->service->process(
            $request,
            GetReceiverDetailsQueryParametersDto::class
        );
    }
}
