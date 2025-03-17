<?php

declare(strict_types=1);

namespace Wise\Core\Exception;

class ObjectExistsException extends CommonLogicException
{
    public function setId(int $entityId): self
    {
        return $this->setTranslation('exceptions.object_exists_exception', ['%id%' => $entityId]);
    }


}
