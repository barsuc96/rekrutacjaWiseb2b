<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Controller\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;
use Wise\User\ApiUi\Dto\Users\GetUsersProfileQueryParametersDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersProfileServiceInterface;

class GetUsersProfileController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly GetUsersProfileServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(path: '/profile', methods: Request::METHOD_GET)]
    #[CommonGetDtoParamAttributes(
        description: 'Pobranie danych profilowych w widoku Moje Konto i podczas procesu inicjacji aplikacji',
        tags: ['Users'],
        parametersDtoClass: GetUsersProfileQueryParametersDto::class
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie pobrano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/GetUserProfileResponseDto", type: "object"
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpił problem podczas przetwarzania danych",
        content: new OA\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
    )]
    public function getAction(Request $request): JsonResponse
    {
        return $this->service->process($request, GetUsersProfileQueryParametersDto::class);
    }
}
