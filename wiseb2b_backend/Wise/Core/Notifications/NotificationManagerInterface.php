<?php

namespace Wise\Core\Notifications;

use Wise\Core\Validator\Enum\ConstraintTypeEnum;

interface NotificationManagerInterface
{
    public static function instance():self;
    public function add(Notification $notification):self;
    public function getMostSevereConstraintType(): ConstraintTypeEnum;
    public function getAllNotifications(): array;
    public function getAllNotificationsGroups(): array;
    public function getAllNotificationsGroupsCount(): array;
    public function getFieldsNotifications(bool $clearUsed = false): array;
    public function flush():array;
    public function clear(): void;
    public function fillCustomPropertyPath(array $customPropertyPath): void;
    public function setCustomPrefixPropertyPath(?string $customPrefixPropertyPath): self;
    public function getCustomPropertyPath(): ?array;
    public function addNotification(ConstraintTypeEnum $constraintTypeEnum, string $message, bool $isFieldRelated = false, string $objectName = null): void;

    //public function flushErrors(): array;
    //public function flushWarnings(): array;
    //public function flushNotices(): array;
}
