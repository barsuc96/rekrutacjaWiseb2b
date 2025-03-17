<?php

namespace Wise\Core\Notifications;

use Wise\Core\Validator\Enum\ConstraintTypeEnum;

class NotificationManager implements NotificationManagerInterface
{
    private ConstraintTypeEnum $mostSevereConstraintType = ConstraintTypeEnum::OK;

    /** @var array<Notification> */
    private array $notifications = [];
    private array $errors = [];
    private array $warnings = [];
    private array $notices = [];
    private static ?self $instance = null;
    private ?array $customPropertyPath = null;
    private ?string $customPrefixPropertyPath = null;

    public function __construct(
        protected readonly NotificationResponseDTOConverterServiceInterface $notificationResponseDTOConverterService
    ){}

    public static function instance(): self
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * @throws UnspecifiedNotificationConstraintType
     */
    public function add(Notification $notification): self
    {
        $this->mostSevereConstraintType = ConstraintTypeEnum::from(max($this->mostSevereConstraintType->value,$notification->type->value));

        if($notification->type === ConstraintTypeEnum::ERROR) {
            $this->errors[] = $notification;
        } else if($notification->type === ConstraintTypeEnum::WARNING) {
            $this->warnings[] = $notification;
        } else if($notification->type === ConstraintTypeEnum::NOTICE) {
            $this->notices[] = $notification;
        }else if($notification->type === ConstraintTypeEnum::OK) {
            $this->notifications[] = $notification;
        }else {
            throw new UnspecifiedNotificationConstraintType("You need to use ConstraintTypeEnum that exists");
        }
        return $this;
    }

    public function flush(): array
    {
        $fields =  $this->getFieldsNotifications();
        $this->clear();

        return $fields;
    }

    public function clear(): void
    {
        $this->errors = [];
        $this->warnings = [];
        $this->notices = [];
        $this->notifications = [];
    }

    public function getAllNotifications(): array
    {
        return [...array_values($this->errors), ...array_values($this->warnings), ...array_values($this->notices),...array_values($this->notifications)];
    }

    public function getAllNotificationsGroups(): array
    {
        return [
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'notices' => $this->notices,
            'notifications' => $this->notifications
        ];
    }

    public function getAllNotificationsGroupsCount(): array
    {
        return [
            'errors' => count($this->errors),
            'warnings' => count($this->warnings),
            'notices' => count($this->notices),
            'notifications' => count($this->notifications)
        ];
    }

    public function getFieldsNotifications(bool $clearUsed = false): array
    {
        $notifications =  $this->getAllNotifications();

        /**
         * Bierzemy tylko te notyfikacje które są powiązane z polem
         * @var Notification $notification
         */
        foreach ($notifications as $notificationKey => $notification){
            if(!$notification->isFieldRelated()){
                unset($notifications[$notificationKey]);
            }

            if(!empty($this->customPrefixPropertyPath)){
                $notification->setPrefixPropertyPath($this->customPrefixPropertyPath);
            }

            // Jeśli flaga clearUsed jest ustawiona na true, to usuwamy z listy notyfikacji te, które zostaną zwrócone
            if($clearUsed){
                if($notification->type === ConstraintTypeEnum::ERROR) {
                    foreach ($this->errors as $key => $error){
                        if($error === $notification){
                            unset($this->errors[$key]);
                        }
                    }
                } else if($notification->type === ConstraintTypeEnum::WARNING) {
                    foreach ($this->warnings as $key => $warning){
                        if($warning === $notification){
                            unset($this->warnings[$key]);
                        }
                    }
                } else if($notification->type === ConstraintTypeEnum::NOTICE) {
                    foreach ($this->notices as $key => $notice){
                        if($notice === $notification){
                            unset($this->notices[$key]);
                        }
                    }
                }else if($notification->type === ConstraintTypeEnum::OK) {
                    foreach ($this->notifications as $key => $notificationValue){
                        if($notificationValue === $notification){
                            unset($this->notifications[$key]);
                        }
                    }
                }
            }
        }

        return $notifications;
    }

    public function fillCustomPropertyPath(array $customPropertyPath): void
    {
        $this->customPropertyPath = $customPropertyPath;
    }

    public function getCustomPropertyPath(): ?array
    {
        return $this->customPropertyPath;
    }

    public function getCustomPrefixPropertyPath(): ?string
    {
        return $this->customPrefixPropertyPath;
    }

    public function setCustomPrefixPropertyPath(?string $customPrefixPropertyPath): self
    {
        $this->customPrefixPropertyPath = $customPrefixPropertyPath;

        return $this;
    }

    public function flushErrors(): array
    {
        return $this->errors;
    }

    public function flushWarnings(): array
    {
        return $this->warnings;
    }
    public function flushNotices(): array
    {
        return $this->notices;
    }

    public function getMostSevereConstraintType(): ConstraintTypeEnum
    {
        return $this->mostSevereConstraintType;
    }

    public function addNotification(ConstraintTypeEnum $constraintTypeEnum, string $message, bool $isFieldRelated = false, string $objectName = null): void
    {
        $notification = new Notification($constraintTypeEnum, $message, $isFieldRelated, $objectName, null);

        if($notification->type === ConstraintTypeEnum::ERROR) {
            $this->errors[] = $notification;
        } else if($notification->type === ConstraintTypeEnum::WARNING) {
            $this->warnings[] = $notification;
        } else if($notification->type === ConstraintTypeEnum::NOTICE) {
            $this->notices[] = $notification;
        } else if($notification->type === ConstraintTypeEnum::OK) {
            $this->notifications[] = $notification;
        }
    }
}
