<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\Clients;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\CommonDetailsAdminApiParametersDto;

class GetClientParametersDto extends CommonDetailsAdminApiParametersDto
{
    #[OA\Property(
        description: 'Czy pobierać też metody płatności?',
        example: false,
    )]
    protected bool $fetchPayments;

    #[OA\Property(
        description: 'Czy pobierać też metody dostawy?',
        example: false,
    )]
    protected bool $fetchDeliveries;

    /**
     * @return bool
     */
    public function isFetchPayments(): bool
    {
        return $this->fetchPayments;
    }

    /**
     * @param bool $fetchPayments
     */
    public function setFetchPayments(bool $fetchPayments): void
    {
        $this->fetchPayments = $fetchPayments;
    }

    /**
     * @return bool
     */
    public function isFetchDeliveries(): bool
    {
        return $this->fetchDeliveries;
    }

    /**
     * @param bool $fetchDeliveries
     */
    public function setFetchDeliveries(bool $fetchDeliveries): void
    {
        $this->fetchDeliveries = $fetchDeliveries;
    }
}
