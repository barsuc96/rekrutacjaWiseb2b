<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Dto\Admin;

use Wise\Core\Dto\AbstractResponseDto;

class GetReplicationHistoryDto extends AbstractResponseDto
{
    /** @var ?ReplicationRequestHistoryDto[] $objects */
    protected ?array $objects = [];

    public function getObjects(): ?array
    {
        return $this->objects;
    }

    public function setObjects(?array $objects): self
    {
        $this->objects = $objects;

        return $this;
    }

    public function addObject(ReplicationRequestHistoryDto $object): self
    {
        $this->objects[] = $object;

        return $this;
    }

}
