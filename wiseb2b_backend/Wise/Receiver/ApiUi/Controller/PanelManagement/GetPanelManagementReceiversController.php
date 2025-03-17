<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\Receiver\ApiUi\Dto\PanelManagement\GetPanelManagementReceiversQueryParametersDto;
use Wise\Receiver\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementReceiversServiceInterface;

class GetPanelManagementReceiversController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetPanelManagementReceiversServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: '/',
        methods: Request::METHOD_GET)
    ]
    #[CommonGetDtoParamAttributes(
        description: 'Pobiera listę odbiorców do wyświetlenia w panelu administracyjnym',
        tags: ['PanelReceivers'],
        parametersDtoClass: GetPanelManagementReceiversQueryParametersDto::class
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/GetPanelManagementReceiversResponseDto", type: "object"
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]

    public function getAction(Request $request): JsonResponse
    {
        // Parametry ze ścieżki (URL Path) przenoszę do Query Parameters
        foreach ($request->attributes->get('_route_params') as $key => $value) {
            $request->query->add([$key => $value]);
        }

        return $this->service->process(
            $request,
            GetPanelManagementReceiversQueryParametersDto::class
        );
    }
}
