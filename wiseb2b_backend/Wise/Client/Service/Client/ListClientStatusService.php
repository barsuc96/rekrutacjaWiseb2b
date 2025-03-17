<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Client\Domain\ClientStatus\ClientStatusRepositoryInterface;
use Wise\Client\Service\Client\Interfaces\ListClientStatusServiceInterface;
use Wise\Core\Service\AbstractListService;

class ListClientStatusService extends AbstractListService implements ListClientStatusServiceInterface
{
    public function __construct(
        private readonly ClientStatusRepositoryInterface $repository,
    ) {
        parent::__construct($repository);
    }
}
