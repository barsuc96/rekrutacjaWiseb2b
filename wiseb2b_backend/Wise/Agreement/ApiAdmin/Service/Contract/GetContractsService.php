<?php

namespace Wise\Agreement\ApiAdmin\Service\Contract;

use Wise\Agreement\ApiAdmin\Service\Contract\Interfaces\GetContractsServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractGetListAdminApiService;

class GetContractsService extends AbstractGetListAdminApiService implements GetContractsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly ListContractServiceInterface $listContractService,
    ){
        parent::__construct($adminApiShareMethodsHelper, $listContractService);
    }

    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        return;
    }
}
