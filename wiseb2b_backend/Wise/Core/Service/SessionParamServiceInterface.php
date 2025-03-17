<?php

namespace Wise\Core\Service;

use Wise\Core\Domain\SessionParam;

interface SessionParamServiceInterface
{
    public function getActiveSessionParam(string $symbol): ?SessionParam;

    public function checkSessionParamExists(string $symbol): bool;

    public function setSessionParam(string $symbol, string $value): void;

    public function deactivateSessionParam(string $symbol): void;
}