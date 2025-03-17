<?php

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PutUsersMessageSettingsRequestDto extends AbstractDto
{
    #[OA\Property(description: 'Czy zgoda jest aktywna?', example: true)]
    protected bool $enabled;

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }


}