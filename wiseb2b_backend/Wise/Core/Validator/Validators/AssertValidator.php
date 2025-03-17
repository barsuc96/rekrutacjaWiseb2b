<?php
namespace Wise\Core\Validator\Validators;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\AbstractModel;
use Wise\Core\Notifications\Notification;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Validator\AbstractValidator;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;

class AssertValidator extends AbstractValidator
{
    public function __construct(
        private ValidatorInterface $validator,
        private readonly NotificationManagerInterface $notificationManager,
    )
    {
        parent::__construct($notificationManager);
    }

    /**
     * @param $object
     * @return bool
     * Obsługujemy tym serwisem walidacyjnym wszystkie rodzaje obiektów
     */
    public function supports(AbstractEntity|AbstractModel|AbstractDto $object):bool
    {
        return true;
    }


    public function validate(AbstractEntity|AbstractModel|AbstractDto $object): void
    {
        $constraintViolation[] = $this->validator->validate($object);
        $this->constraintViolationList->addAll(...$constraintViolation);
    }

    public function handle(): void
    {
        /**
         * @var ConstraintViolation $constraintViolation
         */
        foreach ($this->constraintViolationList as $constraintViolation){

            $constraintType = $this->getConstraintType($constraintViolation);

            $notification = Notification::fromConstraintViolation($constraintViolation, $constraintType);

            $this->notificationManager->add($notification);
        }
    }

    public function getConstraintViolationList(): ConstraintViolationList
    {
        return $this->constraintViolationList;
    }

    /**
     * Zwraca typ błędu
     * @param ConstraintViolation $constraintViolation
     * @return ConstraintTypeEnum
     */
    protected function getConstraintType(ConstraintViolation $constraintViolation): ConstraintTypeEnum
    {
        if($constraintViolation?->getConstraint()?->payload === null || $constraintViolation?->getConstraint()?->payload === []){
            return ConstraintTypeEnum::ERROR;
        }

        try{
            return $constraintViolation?->getConstraint()?->payload['constraintType'];
        }catch (\Exception $exception){
            return ConstraintTypeEnum::ERROR;
        }
    }
}
