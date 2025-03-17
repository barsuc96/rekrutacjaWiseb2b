<?php

namespace Wise\GPSR\ApiAdmin\Dto\GpsrSupplier;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiAdmin\Dto\CommonListAdminApiResponseDto;

class GetGpsrSuppliersDto extends CommonListAdminApiResponseDto
{
    #[OA\Query(
        description: 'Id dostawcy w systemie klienta',
        example: 1,
        fieldEntityMapping: 'idExternal'
    )]
    protected string $id;

    /** @var GetGpsrSupplierDto[] $objects */
    protected ?array $objects;



}

