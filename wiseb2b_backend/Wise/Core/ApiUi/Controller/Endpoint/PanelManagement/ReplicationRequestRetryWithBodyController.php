<?php

namespace Wise\Core\ApiUi\Controller\Endpoint\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationRequestRetryServiceInterface;
use Wise\Core\ApiUi\Dto\PanelManagement\ReplicationRequestRetryWithBodyDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;

/**
 * Controller do ponawiania replikacji z danego requestu (np. po błędzie) w panelu zarządzania
 */
class ReplicationRequestRetryWithBodyController extends AbstractController
{

    public function __construct(
        protected readonly ReplicationRequestRetryServiceInterface $replicationRequestRetryService,
        private readonly UiApiShareMethodsHelper $sharedActionService,
    ) {}

    #[Route(
        path: '/tools/retry/{request_uuid}',
        requirements: ['request_uuid' => '[a-zA-Z0-9-]+'],
        methods: ['POST'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/ReplicationRequestRetryWithBodyDto", type: "object")
    )]
    #[OA\Tag(name: 'PanelLog')]
    public function indexAction(Request $request): Response
    {
        /** @var ReplicationRequestRetryWithBodyDto $dto */
        $dto = $this->sharedActionService->serializer->deserialize($request->getContent(), ReplicationRequestRetryWithBodyDto::class, 'json');

        return $this->replicationRequestRetryService->retry($request->attributes->get('request_uuid'), $dto->getRequestBody(), $dto->getRequestHeaders());
    }
}
