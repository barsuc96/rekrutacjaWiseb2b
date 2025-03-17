<?php

declare(strict_types=1);

namespace Wise\Core\Service\Validator;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Wise\Core\Exception\ObjectValidationException;
use Wise\Core\Model\ValidatableInterface;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;

/**
 * Główny serwis obsługujący walidację domeny
 * @template T of ValidatableInterface *
 */
final class ValidatorService implements ValidatorServiceInterface
{
    public ConstraintViolationList $constraintViolationList;

    public function __construct(
        /** @var iterable<array-key, CustomValidatorInterface> $validators */
        #[TaggedIterator('wise.validator')] protected readonly iterable $validators, // wise.validator
        private readonly Stopwatch $stopwatch
    ) {
    }

    public function validate($object): void
    {
        $this->constraintViolationList = new ConstraintViolationList();
        foreach ($this->validators as $validator) {
            $this->stopwatch->start('validator_' . $validator::class);
            if ($validator->supports($object)) {

                $this->stopwatch->start('validator_validate_');
                $validator->validate($object);
                $this->stopwatch->stop('validator_validate_');
                $this->stopwatch->start('validator_handle_');
                $validator->handle();
                $this->stopwatch->stop('validator_handle_');
            }
            $this->constraintViolationList->addAll($validator->getConstraintViolationList());
            $validator->clearList();
            $this->stopwatch->stop('validator_' . $validator::class);
        }
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        /** @var ConstraintViolation $violation */
        foreach ($this->constraintViolationList as $violation){

            $constraintType = $this->getConstraintType($violation);

            if ($constraintType === ConstraintTypeEnum::ERROR){
                throw new ObjectValidationException();
            }
        }
    }

    /**
     * Usuwa ograniczenia walidacji dla podanych pól
     * @param array $listOfFieldNames
     * @return void
     */
    public function removeConstraintsByFieldNames(array $listOfFieldNames): void
    {
        foreach ($listOfFieldNames as $fieldName){
            foreach ($this->constraintViolationList as $key => $violation){
                if($violation->getPropertyPath() === $fieldName){
                    $this->constraintViolationList->remove($key);
                }
            }
        }
    }

    /**
     * Przekształca nazwę pola na format property path
     * @param string $fieldName
     * @return string
     */
    protected function transformFieldNameToPropertyPathFormat(string$fieldName): string
    {
        if (strpos($fieldName, '.') !== false) {
            $parts = explode('.', $fieldName);
            $transformedParts = array_map(function($part) {
                return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $part));
            }, $parts);

            return implode('.', $transformedParts);
        } else {
            $snakeCaseKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $fieldName));
            return preg_replace('/_/', '.', $snakeCaseKey, 1);
        }
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
