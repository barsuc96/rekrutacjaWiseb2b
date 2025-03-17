<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Controller\Endpoint\Admin;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationRequestObjectRetryServiceInterface;

/**
 * Controller do ponawiania replikacji obiektu z danego requestu (np. po błędzie)
 */
class ReplicationRequestObjectRetryController extends AbstractController
{

    public function __construct(
        protected readonly ReplicationRequestObjectRetryServiceInterface $replicationRequestObjectRetryService
    ) {
    }

    #[Route(
        path: '/api/admin/tools/retry/{request_uuid}/{object_id}',
        requirements: [
            'request_uuid' => '[a-zA-Z0-9-]+',
            'object_id' => '[0-9]+'
        ],
        methods: ['POST'],
    )]
    #[OA\Tag(name: 'AdminTools')]
    public function indexAction(Request $request): JsonResponse
    {
        $response = $this->replicationRequestObjectRetryService->retry(
            $request->attributes->get('request_uuid'),
            (int)$request->attributes->get('object_id')
        );

        return new JsonResponse($response);
    }
}
