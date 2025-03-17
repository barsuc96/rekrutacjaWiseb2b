<?php

declare(strict_types=1);


namespace Wise\Core\Service;

use Wise\Core\Dto\CommonServiceDTO;

class CommonRemoveParams extends CommonServiceDTO
{
    protected array $filters;
    protected bool $continueAfterError = false;

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function isContinueAfterError(): bool
    {
        return $this->continueAfterError;
    }

    public function setContinueAfterError(bool $continueAfterError): void
    {
        $this->continueAfterError = $continueAfterError;
    }
}
