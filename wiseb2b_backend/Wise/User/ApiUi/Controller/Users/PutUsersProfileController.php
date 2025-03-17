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
use Wise\Core\Dto\Attribute\CommonPutDtoParamAttributes;
use Wise\User\ApiUi\Dto\Users\PutUserProfileDto;
use Wise\User\ApiUi\Dto\Users\PutUserProfileRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PutUserProfileServiceInterface;

class PutUsersProfileController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly PutUserProfileServiceInterface $service
    ) {
        parent::__construct($security);
    }

    #[Route(path: '/profile/{user_id}', methods: Request::METHOD_PUT)]
    #[CommonPutDtoParamAttributes(
        description: 'Aktualizacja informacji o użytkowniku - formularz Moje konto',
        tags: ['Users'],
        parametersDtoClass: PutUserProfileRequestDto::class
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PutUserProfileDto", type: "object")
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
    public function putAction(Request $request): JsonResponse
    {
        // Parametry ze ścieżki (URL Path) przenoszę do JSON content
        $content = array_merge(json_decode($request->getContent(), true), [
            'userId' => $request->attributes->getInt('user_id')
        ]);

        return $this->service->process(json_encode($content), PutUserProfileDto::class);
    }
}
