<?php

namespace Wise\Core\Validator;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\AbstractModel;
use Wise\Core\Notifications\Notification;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;

/**
 * Klasa bazowa do realizacji walidatorów obiektów
 */
abstract class AbstractValidator
{
    protected ConstraintViolationListInterface $constraintViolationList;

    public function __construct(
        private readonly NotificationManagerInterface $notificationManager,
    ){
        $this->constraintViolationList = new ConstraintViolationList();
    }

    public function getConstraintViolationList(): ConstraintViolationList
    {
        return $this->constraintViolationList;
    }

    public function handle(): void
    {
        /**
         * @var ConstraintViolation $constraintViolation
         */
        foreach ($this->constraintViolationList as $constraintViolation){
            try{
                $constraintType = $constraintViolation->getConstraint()->payload['constraintType'];
            }catch (\Exception $exception){
                $constraintType = ConstraintTypeEnum::ERROR;
            }

            $notification = Notification::fromConstraintViolation($constraintViolation, $constraintType);

            $this->notificationManager->add($notification);
        }
    }

    public function clearList(): void
    {
        foreach ($this->constraintViolationList as $key => $constraintViolation){
            $this->constraintViolationList->remove($key);
        }
    }

    abstract function supports(AbstractEntity|AbstractModel|AbstractDto $object): bool;
    abstract function validate(AbstractEntity|AbstractModel|AbstractDto $object): void;
}
