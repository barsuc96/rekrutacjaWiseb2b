<?php

namespace Wise\Core\Notifications;

use Symfony\Component\Validator\ConstraintViolation;
use Wise\Core\ApiUi\Dto\FieldInfoDto;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;

/**
 * Serwis konfwertujący dane w NotifyManagerze do standardowych struktur DTO Reposnów
 */
class NotificationResponseDTOConverterService implements NotificationResponseDTOConverterServiceInterface
{
    /**
     * Konwertuje listę notyfikacji do listy FieldInfoDto
     * @param array $notifications
     * @return array
     */
    public function convertToFieldsInfoArray(array $notifications): array
    {
        return array_map(function ($notification) {
            /** @var Notification $notification */
            return $this->convertNotificationToFieldInfoDto($notification);
        }, $notifications);
    }

    /**
     * Konwertuje pojedynczą notyfikację do FieldInfoDto
     * @param Notification $notification
     * @return FieldInfoDto
     */
    public function convertNotificationToFieldInfoDto(Notification $notification): FieldInfoDto
    {
        $messageStyle = match($notification->getConstraintType()) {
            ConstraintTypeEnum::ERROR => ResponseMessageStyle::FAILED->value,
            ConstraintTypeEnum::WARNING => ResponseMessageStyle::WARNING->value,
            ConstraintTypeEnum::NOTICE => ResponseMessageStyle::NOTICE->value,
            ConstraintTypeEnum::OK => ResponseMessageStyle::SUCCESS->value,
        };

        $propertyPath = $notification->getConstraintViolation()->getPropertyPath();
        if(!empty($notification->getPrefixPropertyPath())){
            $propertyPath = $notification->getPrefixPropertyPath() . '.' . $propertyPath;
        }

        return (new FieldInfoDto())
            ->setPropertyPath($propertyPath)
            ->setMessage($notification->getConstraintViolation()->getMessage())
            ->setInvalidValue($notification->getConstraintViolation()->getInvalidValue())
            ->setMessageStyle($messageStyle ?? ResponseMessageStyle::FAILED->value);
    }


    /**
     * Przygotowuje końcową wiadomość do response
     * Jeśli istnieją notyfikacje nie powiązane z polami, to do wiadomości ustawionej w response są doklejane message z tych notyfikacji
     * @param string|null $message
     * @return string
     */
    public function prepareResponseMessage(?string $message, array $notifications): string
    {
        $messageFromLinkedNotification = [];

        /**
         * @var Notification $notification
         */
        foreach ($notifications as $notification){
            if(!$notification->isFieldRelated()){
                $messageFromLinkedNotification[] = $notification->getMessage() ?? $notification->getConstraintViolation()->getMessage();
            }
        }

        if($messageFromLinkedNotification === []){
            return $message;
        }elseif ($message === null){
            return implode("\n", $messageFromLinkedNotification);
        }

        return $message . " \n" . implode(" \n", $messageFromLinkedNotification);
    }
}
