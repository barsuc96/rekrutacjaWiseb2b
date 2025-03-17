<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Controller\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiUi\Service\Clients\Interfaces\PostClientAcceptServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;

class PostClientAcceptController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostClientAcceptServiceInterface $postClientAcceptService,
    ){
        parent::__construct($endpointShareMethodsHelper, $postClientAcceptService);
    }

    #[Route(
        path: '/{id}/accept',
        requirements: ['id' => '\d+'],
        methods: ['POST']
    )]
    #[OAPost(
        description: 'Komenda akceptacji klienta',
        tags: ['Clients'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/CommonCommandParametersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
