<?php

namespace Wise\Core\ApiUi\Dto\PanelManagement;

use DateTimeInterface;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementReplicationRequestsDto extends CommonUiApiListResponseDto
{
    #[OA\Query(
        description: 'Filtrowanie na podstawie endpointu',
        example: '/api/admin/clients',
    )]
    protected ?string $endpoint;

    #[OA\Query(
        description: 'Filtrowanie na podstawie wiadomości',
        example: 'SUCCESS',
    )]
    protected ?string $responseMessage;

    #[OA\Query(
        description: 'Filtrowanie na podstawie unikalny identyfikator requestu w formacie UUID V4',
        example: 'd45a605e-40aa-4fd9-80cb-5cba02665572',
    )]
    protected ?string $uuid;

    #[OA\Query(
        description: 'Data od',
        example: '2023-01-01 00:00:01',
    )]
    protected ?DateTimeInterface $fromDate = null;

    #[OA\Query(
        description: 'Data do',
        example: '2028-01-01 00:00:01',
    )]
    protected ?DateTimeInterface $toDate = null;

    #[OA\Query(
        description: 'Filtrowanie na podstawie statusu',
        example: 3,
    )]
    protected ?int $responseStatus;

    /** @var GetPanelManagementReplicationRequestDto[] */
    protected ?array $items;
}
