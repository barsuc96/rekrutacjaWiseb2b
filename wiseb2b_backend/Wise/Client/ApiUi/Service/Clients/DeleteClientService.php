<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\Clients;

use Wise\Client\ApiUi\Service\Clients\Interfaces\DeleteClientServiceInterface;
use Wise\Client\Service\Client\Interfaces\RemoveClientServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractDeleteUiApiService;

class DeleteClientService extends AbstractDeleteUiApiService implements DeleteClientServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly RemoveClientServiceInterface $removeClientService
    ){
        parent::__construct($sharedActionService, $removeClientService);
    }
}
