<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use Wise\Core\Api\Dto\AbstractRequestDto;

/**
 * Abstract do deklarowania DTO parametrów multiobiektowych PUT i PATCH AdminApi .
 * Klasa bazowa dla DTO parametrów kontrolerów, np. \Wise\Client\ApiAdmin\Dto\Clients\PutClientsDto
 * W klasie właściwej musimy przeciążyć PHP Doc dla pola $objects wskazując właściwą strukturę pojedynczego obiektu w Requescie
 */
class AbstractMultiObjectsAdminApiRequestDto extends AbstractRequestDto
{
    /**
     * @var AbstractSingleObjectAdminApiRequestDto[] $objects
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
