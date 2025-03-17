<?php

namespace Wise\Core\Validator\Constraints;

use Wise\Core\Validator\Enum\ConstraintTypeEnum;

class CustomConstraint extends \Symfony\Component\Validator\Constraint
{

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function setConstraintType(ConstraintTypeEnum $type): self
    {
        $this->payload['constraintType'] = $type;

        return $this;
    }

}
