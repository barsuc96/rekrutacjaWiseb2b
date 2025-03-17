<?php

namespace Wise\Core\Service\Validator;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\AbstractModel;
use Wise\Core\Model\ValidatableInterface;
use Wise\Core\Notifications\NotificationManager;

/**
 * @template T of ValidatableInterface
 */
interface CustomValidatorInterface
{
    public function supports(AbstractEntity|AbstractModel|AbstractDto $object): bool;
    public function validate(AbstractEntity|AbstractModel|AbstractDto $object): void;
    public function getConstraintViolationList(): ConstraintViolationList;
    public function handle(): void;
}
