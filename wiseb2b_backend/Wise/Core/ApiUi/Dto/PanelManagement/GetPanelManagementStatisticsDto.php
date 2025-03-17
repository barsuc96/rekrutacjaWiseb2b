<?php

namespace Wise\Core\ApiUi\Dto\PanelManagement;

use DateTimeInterface;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

class GetPanelManagementStatisticsDto extends CommonUiApiListResponseDto
{
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
        description: 'Status',
        example: 1,
    )]
    protected ?int $status = null;

    /** @var GetPanelManagementStatisticDto[] */
    protected ?array $items;
}
