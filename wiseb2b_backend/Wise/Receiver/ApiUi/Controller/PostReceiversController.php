<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Receiver\ApiUi\Dto\PostReceiversDto;
use Symfony\Component\Security\Core\Security;
use Wise\Receiver\ApiUi\Service\Interfaces\PostReceiversServiceInterface;

class PostReceiversController extends UiApiBaseController
{
    public function __construct(
        Security $security,
        private readonly PostReceiversServiceInterface $service,
    ) {
        parent::__construct($security);
    }
    #[Route(
        path: '',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Receivers')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PostReceiversDto", type: "object")
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Standardowa zwrotka put w przypadku poprawnego zapisu',
        content: new OA\JsonContent(ref: "#/components/schemas/ResponseDto", type: "object")
    )]
    public function postAction(Request $request): JsonResponse
    {
        return $this->service->process($request->getContent(), PostReceiversDto::class);
    }
}
