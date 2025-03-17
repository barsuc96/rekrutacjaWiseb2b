<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Controller;

use JetBrains\PhpStorm\Pure;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Wise\Security\ApiUi\Dto\PostOverLogoutDto;
use Wise\Security\ApiUi\Service\Interfaces\PostOverLogoutServiceInterface;

class PostOverLogoutController extends UiApiBaseController
{
    #[Pure]
    public function __construct(
        Security $security,
        private readonly PostOverLogoutServiceInterface $service,
    ) {
        parent::__construct(
            $security,
        );
    }

    #[Route(
        path: 'auth/overlogout',
        methods: ['POST'],
    )]
    #[OA\Tag(name: 'Auth')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PostOverLogoutDto", type: "object")
    )]
    #[OA\Response(
        ref: "#/components/schemas/ResponseDto",
        response: Response::HTTP_OK
    )]
    public function postOverLogoutAction(Request $request): JsonResponse
    {
        return $this->service->process($request->getContent(), PostOverLogoutDto::class);
    }
}
