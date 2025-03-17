<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientGroup;

use Wise\Client\Domain\ClientGroup\ClientGroupRepositoryInterface;
use Wise\Client\Service\ClientGroup\Interfaces\ClientGroupAdditionalFieldsServiceInterface;
use Wise\Client\Service\ClientGroup\Interfaces\ListClientGroupServiceInterface;
use Wise\Core\Service\AbstractListService;

class ListClientGroupService extends AbstractListService implements ListClientGroupServiceInterface
{
    protected const ENABLE_SEARCH_KEYWORD = true;

    public function __construct(
        private readonly ClientGroupRepositoryInterface $repository,
        private readonly ClientGroupAdditionalFieldsServiceInterface $additionalFieldsService
    ){
        parent::__construct($repository, $additionalFieldsService);
    }

    /**
     * Lista pól, które mają być obsługiwane w filtrowaniu z pola searchKeyword
     * @return string[]
     */
    protected function getDefaultSearchFields(): array
    {
        return [
            'id', 'name'
        ];
    }
}
