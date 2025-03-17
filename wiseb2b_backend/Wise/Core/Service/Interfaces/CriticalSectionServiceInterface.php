<?php

namespace Wise\Core\Service\Interfaces;

interface CriticalSectionServiceInterface
{
    public function lock(string $string): void;

    public function unlock(string $string): void;

}