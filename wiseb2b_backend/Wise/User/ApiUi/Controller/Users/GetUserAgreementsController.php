<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Controller\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\User\ApiUi\Dto\Users\GetUserAgreementQueryParametersDto;
use Symfony\Component\Security\Core\Security;
use Wise\User\ApiUi\Service\Interfaces\GetUsersAgreementsServiceInterface;

class GetUserAgreementsController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetUsersAgreementsServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(path: '/{userId}/agreements', methods: Request::METHOD_GET)]
    #[CommonGetDtoParamAttributes(
        description: 'Lista zgód użytkownika. Użyte na liście użytkowników',
        tags: ['Users'],
        parametersDtoClass: GetUserAgreementQueryParametersDto::class
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/GetUserAgreementsResponseDto", type: "object"
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getUserAgreementsAction(Request $request): JsonResponse
    {
        // Parametry ze ścieżki (URL Path) przenoszę do Query Parameters
        foreach ($request->attributes->get('_route_params') as $key => $value) {
            $request->query->add([$key => $value]);
        }

        return $this->service->process(
            $request,
            GetUserAgreementQueryParametersDto::class,
        );
    }
}
