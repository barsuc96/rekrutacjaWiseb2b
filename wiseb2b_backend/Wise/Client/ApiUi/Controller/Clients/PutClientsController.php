<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Controller\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiUi\Service\Clients\Interfaces\PutClientsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiUi\Controller\AbstractPutController;

class PutClientsController extends AbstractPutController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly PutClientsServiceInterface $putNewClientsService,
    ){
        parent::__construct($endpointShareMethodsHelper, $putNewClientsService);
    }

    #[Route(
        path: '/{id}',
        methods: ['PUT']
    )]
    #[OAPut(
        description: 'Aktualizacja danych klienta',
        tags: ['Clients'],
        requestDto: new OA\JsonContent(ref: "#/components/schemas/PutClientsRequestDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
