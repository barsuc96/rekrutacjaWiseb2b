<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\DataProvider;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Domain\Client\Exceptions\ClientNotFoundException;
use Wise\Client\Service\ClientGroup\Interfaces\GetClientGroupDetailsServiceInterface;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\Core\Service\CommonDetailsParams;
use Wise\MultiStore\Service\Store\Interfaces\GetStoreDetailsServiceInterface;

class ClientStoreProvider extends AbstractAdditionalFieldProvider implements ClientDetailsProviderInterface
{
    public const FIELD = 'store';

    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly GetClientGroupDetailsServiceInterface $getClientGroupDetailsService,
        private readonly ContainerBagInterface $configParams,
        private readonly GetStoreDetailsServiceInterface $getStoreDetailsService
    ){}

    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
        /** @var Client $client */
        $client = $this->clientRepository->find($entityId);

        if (!$client) {
            throw ClientNotFoundException::id($entityId);
        }

        if($client->getClientGroupId() !== null){
            $params = new CommonDetailsParams();
            $params
                ->setId($client->getClientGroupId())
                ->setFields([
                    'id' => 'id',
                    'storeId' => 'storeId'
                ]);

            $clientGroup = ($this->getClientGroupDetailsService)($params)->read();

            if(!empty($clientGroup['storeId'])){

                $params = new CommonDetailsParams();
                $params
                    ->setId($clientGroup['storeId'])
                    ->setFields([
                        'id' => 'id',
                        'name' => 'name',
                        'symbol' => 'symbol'
                    ]);

                $store = ($this->getStoreDetailsService)($params)->read();

                return [
                    'id' => $store['id'],
                    'symbol' => $store['symbol'],
                    'name' => $store['name']
                ];
            }
        }


        return [
            'id' => null,
            'symbol' => null,
            'name' => null
        ];
    }
}
