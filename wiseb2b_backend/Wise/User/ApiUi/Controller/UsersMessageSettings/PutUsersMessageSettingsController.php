<?php

namespace Wise\User\ApiUi\Controller\UsersMessageSettings;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Core\Dto\Attribute\CommonPutDtoParamAttributes;
use Wise\User\ApiUi\Dto\Users\PutUsersMessageSettingsDto;
use Wise\User\ApiUi\Dto\Users\PutUsersMessageSettingsQueryParametersDto;
use Wise\User\ApiUi\Service\Interfaces\PutUsersMessageSettingsServiceInterface;

class PutUsersMessageSettingsController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly PutUsersMessageSettingsServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[
        Route(
            path: '/{userId}/message-settings/{messageSettingsId}',
            requirements: ['userId' => '\d+', 'messageSettingsId' => '\d+'],
            methods: Request::METHOD_PUT
        ),
        CommonPutDtoParamAttributes(
            description: 'Aktualizacja zgody na komunikaty, użyte w dashboardzie',
            tags: ['Users'],
            parametersDtoClass: PutUsersMessageSettingsQueryParametersDto::class
        ),
        OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/PutUsersMessageSettingsRequestDto",
                type: "object"
            )
        ),
        OA\Response(
            response: Response::HTTP_OK,
            description: "Poprawnie zapisano dane",
            content: new OA\JsonContent(
                ref: "#/components/schemas/Common200FormResponseDto",
                type: "object"
            ),
        ),
        OA\Response(
            response: Response::HTTP_BAD_REQUEST,
            description: "Wystąpiły błędy",
            content: new OA\JsonContent(
                ref: "#/components/schemas/Common422FormResponseDto",
                type: "object"
            ),
        )
    ]
    public function putAction(Request $request): JsonResponse
    {
        // Parametry ze ścieżki (URL Path) przenoszę do JSON content
        $content = array_merge(json_decode($request->getContent(), true), [
            'userId' => $request->attributes->getInt('userId'),
            'messageSettingsId' => $request->attributes->getInt('messageSettingsId'),
        ]);

        return $this->service->process(json_encode($content), PutUsersMessageSettingsDto::class);
    }
}
