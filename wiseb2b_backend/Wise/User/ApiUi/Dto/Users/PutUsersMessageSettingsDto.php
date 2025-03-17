<?php

namespace Wise\User\ApiUi\Dto\Users;

class PutUsersMessageSettingsDto extends PutUsersMessageSettingsQueryParametersDto
{
    protected bool $enabled = false;

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}