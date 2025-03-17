<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Controller;

use Nelmio\ApiDocBundle\Annotation\Security as ApiSecurity;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiUi\Controller\UiApiBaseController;
use Symfony\Component\Security\Core\Security;
use JetBrains\PhpStorm\Pure;
use Wise\Security\ApiUi\Dto\PostPasswordResetDto;
use Wise\Security\ApiUi\Service\Interfaces\PostPasswordResetServiceInterface;

class PostPasswordResetController extends UiApiBaseController
{
    #[Pure]
    public function __construct(
        Security $security,
        private readonly PostPasswordResetServiceInterface $service,
    ) {
        parent::__construct(
            $security,
        );
    }

    #[Route(
        path: 'auth/password-reset',
        methods: ['POST'],
    )]
    #[OA\Tag(name: 'Auth')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PostPasswordResetDto", type: "object")
    )]
    #[OA\Response(
        ref: "#/components/schemas/ResponseDto",
        response: Response::HTTP_OK
    )]
    #[ApiSecurity(name: null)]
    public function postPasswordResetAction(Request $request): JsonResponse
    {
        return $this->service->process($request->getContent(), PostPasswordResetDto::class);
    }
}
