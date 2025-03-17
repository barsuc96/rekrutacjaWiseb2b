<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientPaymentMethod;

use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethodRepositoryInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\ListClientPaymentMethodServiceInterface;
use Wise\Core\Service\AbstractListService;

class ListClientPaymentMethodService extends AbstractListService implements ListClientPaymentMethodServiceInterface
{
    public function __construct(
        private readonly ClientPaymentMethodRepositoryInterface $repository
    )
    {
        parent::__construct($repository);
    }
}
