<?php

namespace Wise\Agreement\ApiUi\Controller\Contract;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Agreement\ApiUi\Service\Contract\Interfaces\GetContractsServiceInterface;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Controller\AbstractGetListController;

class GetContractsController extends AbstractGetListController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly GetContractsServiceInterface $service
    ) {
        parent::__construct($endpointShareMethodsHelper, $service);
    }

    #[Route(path: '/', methods: Request::METHOD_GET)]
    #[OAGet(
        description: 'Zwraca listę umów i zgód użytkownika. Wykorzystywane na front aby określać jakie zgody musi uzupełnić klient',
        tags: ['Contract'],
        responseDto: new OA\JsonContent(ref: "#/components/schemas/GetContractsDto", type: "object")
    )]
    public function getAction(Request $request): JsonResponse
    {
        return parent::getAction($request);
    }
}
