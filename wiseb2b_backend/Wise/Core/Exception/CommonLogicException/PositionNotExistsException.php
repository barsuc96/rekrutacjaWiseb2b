<?php

namespace Wise\Core\Exception\CommonLogicException;

use Wise\Core\Exception\CommonLogicException;

class PositionNotExistsException extends CommonLogicException
{
    public function setId(int $positionId): self
    {
        return $this->setTranslation('exceptions.position_not_exists_exception', ['%id%' => $positionId]);
    }
}
