<?php

namespace Wise\Core\ApiUi\Dto\PanelManagement;

use DateTimeInterface;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementReplicationObjectsFailedDto extends CommonUiApiListResponseDto
{
    #[OA\Query(
        description: 'Filtrowanie na podstawie id_requestu',
        example: 3,
    )]
    protected ?int $idRequest;

    #[OA\Query(
        description: 'Wyszukiwarka',
        example: 'clients',
    )]
    protected ?string $searchKeyword;

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


    /** @var GetPanelManagementReplicationObjectFailedDto[] */
    protected ?array $items;
}
