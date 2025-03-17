<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Controller\Endpoint\Admin;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationRequestRetryServiceInterface;

/**
 * Controller do ponawiania replikacji z danego requestu (np. po błędzie)
 */
class ReplicationRequestRetryController extends AbstractController
{

    public function __construct(
        protected readonly ReplicationRequestRetryServiceInterface $replicationRequestRetryService
    ) {
    }

    #[Route(
        path: '/api/admin/tools/retry/{request_uuid}',
        requirements: ['request_uuid' => '[a-zA-Z0-9-]+'],
        methods: ['POST'],
    )]
    #[OA\Tag(name: 'AdminTools')]
    public function indexAction(Request $request): Response
    {
        return $this->replicationRequestRetryService->retry($request->attributes->get('request_uuid'));
    }
}
