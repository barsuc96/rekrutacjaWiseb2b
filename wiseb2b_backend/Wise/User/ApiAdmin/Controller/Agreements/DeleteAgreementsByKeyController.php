<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Controller\Agreements;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\Controller\AdminApiBaseController;
use Wise\Core\Security\Constants\ScopeNames;
use Wise\User\ApiAdmin\Dto\Agreements\DeleteAgreementsByKeyAttributesDto;
use Wise\User\ApiAdmin\Service\Agreements\Interfaces\DeleteAgreementsByKeyServiceInterface;

class DeleteAgreementsByKeyController extends AdminApiBaseController
{
    protected array $requiredApiScopes = [
        ScopeNames::GENERAL_ACCESS,
        ScopeNames::GENERAL_DELETE,
        ScopeNames::AGREEMENTS_ALL,
        ScopeNames::AGREEMENTS_DELETE,
    ];

    public function __construct(
        private readonly DeleteAgreementsByKeyServiceInterface $deleteAgreementsService
    ) {
    }

    #[Route(
        path: '/{agreement_id}',
        requirements: [
            'agreement_id' => '([a-zA-Z0-9-_])+',
        ],
        methods: ['DELETE']
    )]
    #[OA\Tag(name: 'Agreements')]
    #[OA\Parameter(
        name: 'x-request-uuid',
        description: 'UUID requestu',
        in: 'header',
        schema: new OA\Schema(type: 'string'),
        example: '49c9aa13-c5c3-474b-a874-755f9d553779'
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Zwrotka w przypadku znalezionych i poprawienie usuniętych obiektów",
        content: new OA\JsonContent(ref: "#/components/schemas/CommonDeleteResponseAdminApiDto", type: "object")
    )]
    #[OA\Response(
        response: Response::HTTP_UNAUTHORIZED,
        description: 'Błędny token autoryzacyjny',
        content: new OA\JsonContent(ref: "#/components/schemas/UnauthorizedResponseDto", type: "object")
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Niepoprawne dane wejściowe',
        content: new OA\JsonContent(ref: "#/components/schemas/InvalidInputDataResponseDto", type: "object")
    )]
    public function deleteAgreementsAction(Request $request): JsonResponse
    {
        return $this->deleteAgreementsService->process(
            $request->headers->all(),
            $request->attributes->all()['_route_params'],
            DeleteAgreementsByKeyAttributesDto::class
        );
    }
}
