<?php

namespace Wise\User\ApiUi\Controller\Contract;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetListController;
use Wise\User\ApiUi\Service\Contract\Interfaces\GetUserContractServiceInterface;

/**
 * Endpoint zwracający zgody (zaakceptowane i te, które może zaakceptować) użytkownik w panelu użytkownika
 */
class GetUserContractController extends AbstractGetListController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetUserContractServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/', methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca listę umów i zgód użytkownika. Wykorzystywane na front w panelu użytkownika',
        tags: ['Users'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetUserContractsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}

