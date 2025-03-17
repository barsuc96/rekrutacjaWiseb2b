<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Receiver\ApiUi\Dto\PutReceiversRequestDto;
use Wise\Receiver\ApiUi\Service\Interfaces\PutReceiversServiceInterface;

class PutReceiversController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        protected readonly PutReceiversServiceInterface $service,
    ) {
        parent::__construct($security);
    }

    #[Route(
        path: '/{receiverId}',
        methods: ['PUT']
    )]
    #[OA\Tag(name: 'Receivers')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PutReceiversRequestDto", type: "object")
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
    public function putReceiversAction(Request $request): JsonResponse
    {
        // Parametry ze ścieżki (URL Path) przenoszę do JSON content
        $content = array_merge(json_decode($request->getContent(), true), [
            'receiverId' => $request->attributes->getInt('receiverId'),
        ]);

        return $this->service->process(json_encode($content), PutReceiversRequestDto::class);
    }
}
