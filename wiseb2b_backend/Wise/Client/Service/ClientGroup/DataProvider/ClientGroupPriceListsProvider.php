<?php

namespace Wise\Client\Service\ClientGroup\DataProvider;

use Wise\Client\Domain\ClientGroup\ClientGroup;
use Wise\Client\Domain\ClientGroup\ClientGroupRepositoryInterface;
use Wise\Client\Domain\ClientGroup\Exceptions\ClientGroupNotFoundException;
use Wise\Client\Domain\ClientPriceList\ClientPriceList;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;

class ClientGroupPriceListsProvider extends AbstractAdditionalFieldProvider implements ClientGroupDetailsProviderInterface
{
    public const FIELD = 'listPriceLists';

    public function __construct(
        private readonly ClientGroupRepositoryInterface $clientGroupRepository,
    ) {}

    public function getFieldValue($clientGroupId, ?array $cacheData = null): mixed
    {
        /**
         * @var ClientGroup $clientGroup
         */
        $clientGroup = $this->clientGroupRepository->find($clientGroupId);
        if(!$clientGroup) {
            throw ClientGroupNotFoundException::id($clientGroupId);
        }

        $priceLists = [];
        /**
         * @var ClientPriceList $clientPriceList
         */
        foreach ($clientGroup->getPriceLists() as $clientPriceList) {
            $priceLists[] = [
                'priority' => $clientPriceList->getPriority(),
                'priceListId' => $clientPriceList->getPriceListId(),
                'storeId' => $clientPriceList->getStoreId(),
            ];
        }

        if(empty($priceLists)){
            return [];
        }

        $this->addPriceListsIdExternalData($priceLists);

        return $priceLists;
    }

    /**
     * Dodaje dodatkowe informacje do priceList
     * @param array $priceLists
     * @return void
     */
    protected function addPriceListsIdExternalData(array &$priceLists): void
    {
        $priceLists = [];
    }
}
