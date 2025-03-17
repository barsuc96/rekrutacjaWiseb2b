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
use Wise\Receiver\ApiUi\Service\Interfaces\GetReceiverDetailsByTypeServiceInterface;

class GetReceiverDetailsByTypeController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetReceiverDetailsByTypeServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: '/type/{clientId}/{receiverType}',
        methods: ['GET']
    )]
    #[CommonGetDtoParamAttributes(
        description: 'Szczegóły odbiorcy na podstawie typu (zalogowanego użytkownika)',
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
        // Parametry ze ścieżki (URL Path) przenoszę do Query Parameters
        foreach ($request->attributes->get('_route_params') as $key => $value) {
            $request->query->add([$key => $value]);
        }

        return $this->service->process(
            $request,
            GetReceiverDetailsQueryParametersDto::class
        );
    }
}
