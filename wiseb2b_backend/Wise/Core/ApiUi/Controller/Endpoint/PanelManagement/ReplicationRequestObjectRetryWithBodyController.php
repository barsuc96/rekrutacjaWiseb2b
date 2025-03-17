<?php

namespace Wise\Core\ApiUi\Controller\Endpoint\PanelManagement;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationRequestObjectRetryServiceInterface;
use Wise\Core\ApiUi\Dto\PanelManagement\ReplicationRequestObjectRetryWithBodyDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;

class ReplicationRequestObjectRetryWithBodyController extends AbstractController
{

    public function __construct(
        protected readonly ReplicationRequestObjectRetryServiceInterface $replicationRequestObjectRetryService,
        private readonly UiApiShareMethodsHelper $sharedActionService,
    ) {}

    #[Route(
        path: '/tools/retry/{request_uuid}/{object_id}',
        requirements: ['request_uuid' => '[a-zA-Z0-9-]+'],
        methods: ['POST'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/ReplicationRequestObjectRetryWithBodyDto", type: "object")
    )]
    #[OA\Tag(name: 'PanelLog')]
    public function indexAction(Request $request): Response
    {
        /** @var ReplicationRequestObjectRetryWithBodyDto $dto */
        $dto = $this->sharedActionService->serializer->deserialize($request->getContent(), ReplicationRequestObjectRetryWithBodyDto::class, 'json');


        $response = $this->replicationRequestObjectRetryService->retry(
            $request->attributes->get('request_uuid'),
            $request->attributes->getInt('object_id'),
            $dto->getRequestBody(),
        );

        return new JsonResponse($response);
    }
}
