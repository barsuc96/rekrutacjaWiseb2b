<?php

namespace Wise\Core\Notifications;

use Wise\Core\ApiUi\Dto\FieldInfoDto;

interface NotificationResponseDTOConverterServiceInterface
{
    public function convertToFieldsInfoArray(array $notifications): array;
    public function convertNotificationToFieldInfoDto(Notification $notification): FieldInfoDto;
}
