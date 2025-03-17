<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Controller\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiUi\Service\Clients\Interfaces\PostClientsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Controller\AbstractPostController;

class PostClientsController extends AbstractPostController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PostClientsServiceInterface $postClientsService,
    ){
        parent::__construct($endpointShareMethodsHelper, $postClientsService);
    }

    #[Route(
        path: '',
        methods: ['POST']
    )]
    #[OAPost(
        description: 'Dodanie nowego klienta',
        tags: ['Clients'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PostClientsRequestDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
