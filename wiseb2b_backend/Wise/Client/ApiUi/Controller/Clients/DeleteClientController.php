<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Controller\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiUi\Service\Clients\Interfaces\DeleteClientServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiUi\Controller\AbstractDeleteController;

class DeleteClientController extends AbstractDeleteController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly DeleteClientServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '/{id}',
        requirements: ['id' => '\d+'],
        methods: Request::METHOD_DELETE
    )]
    #[OA\Tag(name: 'Clients')]
    #[OADelete(
        description: 'Endpoint umożliwiający usunięcie klienta',
        tags: ['Clients'],
        parametersDto: new OA\JsonContent(ref: "#/components/schemas/DeleteClientParametersDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
