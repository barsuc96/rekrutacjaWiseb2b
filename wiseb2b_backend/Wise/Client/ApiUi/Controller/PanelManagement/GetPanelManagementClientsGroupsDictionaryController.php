<?php

namespace Wise\Client\ApiUi\Controller\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Client\ApiUi\Dto\PanelManagement\GetPanelManagementClientsGroupsQueryParametersDto;
use Wise\Client\ApiUi\Service\PanelManagement\Interfaces\GetPanelManagementClientsGroupsDictionaryInterface;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;

class GetPanelManagementClientsGroupsDictionaryController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetPanelManagementClientsGroupsDictionaryInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: '/clients-groups/dictionary',
        methods: Request::METHOD_GET,
    )]
    #[CommonGetDtoParamAttributes(
        description: 'Zwraca listę grup klientów dostępnych w systemie. Do wykorzystania w panelu administracyjnym dla listingu opcji do wyboru.',
        tags: ['PanelClients'],
        parametersDtoClass: GetPanelManagementClientsGroupsQueryParametersDto::class,
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(ref: "#/components/schemas/GetPanelManagementClientsGroupsDictionaryResponseDto", type: "object"),
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
            GetPanelManagementClientsGroupsQueryParametersDto::class
        );
    }
}
