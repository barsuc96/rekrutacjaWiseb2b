<?php

declare(strict_types=1);

namespace Wise\Core\Domain;

/**
 * Klasa służąca do ujednolicenia zwracanych danych z serwisów domenowych
 *  Zawiera parametr $status informujący o tym, jak przebiegła akcja serwisu domeny oraz $value - wartość zwracana
 */
class CommonDomainServiceResult
{
    public const STATUS_OK = 'ok';
    public const STATUS_WARNING = 'warning';
    public const STATUS_ERROR = 'error';

    protected string $status;
    protected mixed $value;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return self
     */
    public function setValue(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }
}
