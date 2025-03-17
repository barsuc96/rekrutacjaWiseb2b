<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Controller\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Client\ApiUi\Service\Clients\Interfaces\GetClientsDetailsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetDetailsController;

/**
 * Endpoint do pobrania szczegółów klienta. Użyte na liście klientów w dashboardzie przy edycji kleinta.
 */
class GetClientDetailsController extends AbstractGetDetailsController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetClientsDetailsServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(
        path: '/{id}',
        requirements: ['id' => '\d+'],
        methods: Request::METHOD_GET
    )]
    #[OAGet(
        description: 'Lista klientów. Użyte na stronie listy klientów w dashboardzie.',
        tags: ['Clients'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/ClientResponseDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
