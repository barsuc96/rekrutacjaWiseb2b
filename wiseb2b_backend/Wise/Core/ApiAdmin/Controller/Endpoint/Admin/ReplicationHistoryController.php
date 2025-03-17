<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Controller\Endpoint\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\Dto\Admin\ReplicationHistoryQueryParametersDto;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationHistoryServiceInterface;
use Wise\Core\Dto\Attribute\CommonGetDtoParamAttributes;

/**
 * Controller do pobierania historii replikacji
 */
class ReplicationHistoryController extends AbstractController
{
    public function __construct(
        protected readonly ReplicationHistoryServiceInterface $replicationHistoryService
    ) {
    }

    #[Route(
        path: '/api/admin/tools/history',
        methods: ['GET'],
    )]
    #[CommonGetDtoParamAttributes(
        description: 'Pobieranie historii replikacji',
        tags: ['AdminTools'],
        parametersDtoClass: ReplicationHistoryQueryParametersDto::class
    )]
    public function indexAction(Request $request): JsonResponse
    {
        return $this->replicationHistoryService->get($request->query);
    }
}
