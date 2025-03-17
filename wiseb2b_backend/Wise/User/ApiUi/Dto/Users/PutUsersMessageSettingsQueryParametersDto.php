<?php

namespace Wise\User\ApiUi\Dto\Users;

use Wise\Core\ApiUi\Dto\CommonQueryParametersDto;

class PutUsersMessageSettingsQueryParametersDto extends CommonQueryParametersDto
{
    protected int $userId;
    protected int $messageSettingsId;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getMessageSettingsId(): int
    {
        return $this->messageSettingsId;
    }

    /**
     * @param int $messageSettingsId
     */
    public function setMessageSettingsId(int $messageSettingsId): void
    {
        $this->messageSettingsId = $messageSettingsId;
    }
}