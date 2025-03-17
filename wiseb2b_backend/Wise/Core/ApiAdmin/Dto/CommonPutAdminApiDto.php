<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use Wise\Core\Dto\AbstractDto;

/**
 * Obiekt zawierający standardowe pola do filtrowania użyte w każdym zapytaniu GET w ApiAdmin Api
 * @deprecated zastąpione przez \Wise\Core\ApiAdmin\Dto\AbstractMultiObjectsAdminApiRequestDto
 */
abstract class CommonPutAdminApiDto extends AbstractDto
{
    /**
     * @var AbstractDto[] $objects
     */
    protected array $objects = [];

    public function getObjects(): array
    {
        return $this->objects;
    }

    public function setObjects(array $objects): self
    {
        $this->objects = $objects;

        return $this;
    }
}
