<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\PanelManagement\Users;

use Wise\Core\Dto\AbstractResponseDto;

class GetPanelManagementUsersResponseDto extends AbstractResponseDto
{
    /** @var GetPanelManagementUserResponseDto[] */
    protected ?array $objects;

    public function getObjects(): ?array
    {
        return $this->objects;
    }

    public function setObjects(?array $objects): self
    {
        $this->objects = $objects;

        return $this;
    }
}
