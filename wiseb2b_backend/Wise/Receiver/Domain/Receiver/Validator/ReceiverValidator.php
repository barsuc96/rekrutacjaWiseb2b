<?php
namespace Wise\Receiver\Domain\Receiver\Validator;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Wise\Core\Model\ValidatableInterface;
use Wise\Core\Notifications\Notification;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Validator\AbstractValidator;
use Wise\Core\Validator\Constraints\CustomConstraint;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;
use Wise\Receiver\Domain\Receiver\Receiver;

/**
 * Przykład domenowej obsługi policy
 */
class ReceiverValidator extends AbstractValidator
{

    public function __construct(
        private readonly NotificationManagerInterface $notificationManager,
    )
    {
        parent::__construct($notificationManager);
    }

    /**
     * @param $object
     * @return bool
     * Obsługujemy tym serwisem walidacyjnym wszystkie obiekty typu Receiver
     */
    public function supports($object):bool
    {
        return $object instanceof Receiver;
    }

    /**
     * @param ValidatableInterface $object
     * @return void
     */
    public function validate($object): void
    {
        /** @var Receiver $object */
        $receiver = $object;

        if(empty($receiver->getName())){
            $this->constraintViolationList->add(
                new ConstraintViolation(
                    message: 'Musisz podać nazwę firmy',
                    messageTemplate: null,
                    parameters: [],
                    root: $receiver,
                    propertyPath: 'name',
                    invalidValue: $receiver->getName(),
                    constraint: (new CustomConstraint())->setConstraintType(ConstraintTypeEnum::ERROR)
                )
            );
        }

        if(empty($receiver->getFirstName())){
            $this->constraintViolationList->add(
                new ConstraintViolation(
                    message: 'Pole imię nie może być puste',
                    messageTemplate: null,
                    parameters: [],
                    root: $receiver,
                    propertyPath: 'firstName',
                    invalidValue: $receiver->getFirstName(),
                    constraint: (new CustomConstraint())->setConstraintType(ConstraintTypeEnum::ERROR)
                )
            );
        }
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

            $notification = Notification::fromConstraintViolation($constraintViolation, $constraintType, Receiver::class);

            $this->notificationManager->add($notification);
        }
    }

    public function getConstraintViolationList(): ConstraintViolationList
    {
        return $this->constraintViolationList;
    }
}
