<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto;

use Symfony\Component\Serializer\Annotation\Ignore;
use Wise\Core\Dto\AbstractDto;

/**
 * Obiekt zawierający standardowe pola do filtrowania użyte w każdym zapytaniu POST w ApiUi
 * @deprecated Nowe endpointy tego nie potrzebują
 */
abstract class CommonPostUiApiDto extends AbstractDto
{
    /**
     * @var AbstractDto[] $objects
     * TODO Czy w UI API ma zostać $objects? Wydaje mi się, że to jest kalka z Admin API i nigdy nie będzie obsługiwana
     */
    #[Ignore]
    protected array $objects;

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
