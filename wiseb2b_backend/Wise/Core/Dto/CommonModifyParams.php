<?php

declare(strict_types=1);


namespace Wise\Core\Dto;

class CommonModifyParams extends CommonServiceDTO
{
    protected bool $mergeNestedObjects = false;

    public function createFromCommonServiceDTO(CommonServiceDTO $commonServiceDTO)
    {
        $this->data = $commonServiceDTO->data;

        return $this;
    }

    public function getMergeNestedObjects(): bool
    {
        return $this->mergeNestedObjects;
    }

    public function setMergeNestedObjects(bool $mergeNestedObjects): self
    {
        $this->mergeNestedObjects = $mergeNestedObjects;

        return $this;
    }
}
