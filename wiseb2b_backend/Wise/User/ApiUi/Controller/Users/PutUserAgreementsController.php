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
use Wise\User\ApiUi\Dto\Users\PutUserAgreementsParametersDto;
use Wise\User\ApiUi\Dto\Users\PutUserAgreementsRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PutUserAgreementsServiceInterface;

class PutUserAgreementsController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        protected readonly PutUserAgreementsServiceInterface $service,
    ) {
        parent::__construct($security);
    }

    #[Route(path: '/{userId}/agreements', methods: Request::METHOD_PUT)]
    #[CommonPutDtoParamAttributes(
        description: 'Zatwierdzenie/wycofanie zgody. Dziwne, że nie ma id\'ka usera',
        tags: ['Users'],
        parametersDtoClass: PutUserAgreementsParametersDto::class,
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PutUserAgreementsRequestDto", type: "object")
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Poprawnie zapisano dane",
        content: new OA\JsonContent(
            ref: "#/components/schemas/Common200FormResponseDto",
            type: "object"
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "Wystąpiły błędy",
        content: new OA\JsonContent(
            ref: "#/components/schemas/Common422FormResponseDto",
            type: "object"
        ),
    )]
    public function putUsersAgreementsAction(Request $request): JsonResponse
    {
        // Parametry ze ścieżki (URL Path) przenoszę do JSON content
        $content = array_merge(json_decode($request->getContent(), true), [
            'userId' => $request->attributes->getInt('userId'),
        ]);

        return $this->service->process(json_encode($content), PutUserAgreementsRequestDto::class);
    }
}
