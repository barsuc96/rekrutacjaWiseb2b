<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Dto\Admin;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;

class ReplicationHistoryQueryParametersDto extends CommonGetAdminApiDto
{
    #[OA\Property(
        description: 'UUID requestu',
        example: '49c9aa13-c5c3-474b-a874-755f9d553779',
    )]
    #[Assert\Uuid(
        message: 'Niepoprawny format UUID'
    )]
    protected string $requestUuid;

    #[OA\Property(
        description: 'Czy pobierać replikowane obiekty?',
        example: false,
    )]
    protected bool $fetchObjects;

    #[OA\Property(
        description:
            'Status zwrotki:<br>
            success => 1<br>
            failed => 0<br>
            in progress => 2<br>
            partially failed => -1',
        example: 1,
    )]
    #[Assert\Choice(
        choices: [-1,0,1,2],
        message: 'Niepoprawny status zwrotki'
    )]
    protected int $responseStatus;

    #[OA\Property(
        description: 'Metoda requestu',
        example: 'PUT'
    )]
    #[Assert\Choice(
        choices: ['PUT', 'POST', 'PATCH', 'DELETE', 'GET'],
        message: 'Niepoprawna metoda requestu'
    )]
    protected string $method;

    public function getFetchObjects(): bool
    {
        return $this->fetchObjects;
    }

    public function setFetchObjects(bool $fetchObjects): self
    {
        $this->fetchObjects = $fetchObjects;

        return $this;
    }

    public function getRequestUuid(): string
    {
        return $this->requestUuid;
    }

    public function setRequestUuid(string $requestUuid): self
    {
        $this->requestUuid = $requestUuid;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    public function setDateFrom(string $dateFrom): self
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    public function getDateTo(): string
    {
        return $this->dateTo;
    }

    public function setDateTo(string $dateTo): self
    {
        $this->dateTo = $dateTo;

        return $this;
    }
}
