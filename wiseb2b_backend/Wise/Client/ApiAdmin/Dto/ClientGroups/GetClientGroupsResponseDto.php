<?php

namespace Wise\Client\ApiAdmin\Dto\ClientGroups;

use Wise\Core\ApiAdmin\Dto\CommonListAdminApiResponseDto;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

class GetClientGroupsResponseDto extends CommonListAdminApiResponseDto
{
    #[OA\Query(
        description: 'Id grupy klienckiej identyfikujące grupę w ERP',
        example: '1',
        fieldEntityMapping: 'idExternal'
    )]
    protected string $id;

    /** @var GetClientGroupResponseDto[] $objects */
    protected ?array $objects;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
