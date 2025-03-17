<?php

namespace Wise\Core\Service\Validator;

interface ValidatorServiceInterface
{
    public function validate($object): void;
    public function handle(): void;
    public function removeConstraintsByFieldNames(array $listOfFieldNames): void;
}
