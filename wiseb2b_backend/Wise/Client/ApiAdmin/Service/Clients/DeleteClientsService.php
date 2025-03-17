<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Service\Clients;

use Wise\Client\ApiAdmin\Service\Clients\Interfaces\DeleteClientsServiceInterface;
use Wise\Client\Service\Client\Interfaces\RemoveClientServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteAdminApiService;

class DeleteClientsService extends AbstractDeleteAdminApiService implements DeleteClientsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        protected readonly RemoveClientServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper, $service);
    }
}
