<?php

declare(strict_types=1);

namespace Wise\Client\Domain;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\Events\ClientTaxNumberHasChangedEvent;
use Wise\Client\Domain\Client\Exceptions\ClientUniqueTaxNumberException;
use Wise\Client\Domain\ClientGroup\ClientGroup;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Client\Service\ClientGroup\Interfaces\GetClientGroupDetailsServiceInterface;
use Wise\Client\Service\ClientGroup\Interfaces\ListClientGroupServiceInterface;
use Wise\Client\WiseClientExtension;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\QueryJoin;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\MultiStore\Service\Store\Interfaces\StoreHelperInterface;

/**
 * Listener weryfikuje unikalność numerów NIP
 */
class ClientUniqueTaxNumberListener
{

    public function __construct(
        private readonly ListClientsServiceInterface $listClientsService,
        private readonly ConfigServiceInterface $configService,
        private readonly StoreHelperInterface $storeHelper,
        private readonly GetClientGroupDetailsServiceInterface $getClientGroupDetailsService,
        private readonly ListClientGroupServiceInterface $listClientGroupService
    ){}

    public function __invoke(ClientTaxNumberHasChangedEvent $event): void
    {
        /** @var Client $client */
        $client = $event->getClient();

        // Pobranie z konfiguracji czy mamy sprawdzać unikalność
        if(!$this->verifyUniqueTaxNumber() || empty($client->getTaxNumber())){
            return;
        }

        // Pobieram listę grup klientów na postawie klienta
        $clientGroupIds = $this->getClientGroupsIds($client);


        // Pobieram listę klientów z takim samym numerem NIP (w obrębie pobranych grup klienckich)
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('taxNumber', $client->getTaxNumber()),
                new QueryFilter('clientGroupId', $clientGroupIds, QueryFilter::COMPARATOR_IN),
            ])
            ->setFields([
                'id' => 'id',
                'taxNumber' => 'taxNumber',
            ]);

        $clients = ($this->listClientsService)($params)->read();

        if(empty($clients)){
            return;
        }


        $uniqueTaxNumber = true;

        // Sprawdzam czy numer NIP jest unikalny wśród pobranych klientów
        foreach ($clients as $foundedClient){
            if($foundedClient['id'] != $client->getId()){
                $uniqueTaxNumber = false;
                break;
            }
        }

        if($uniqueTaxNumber === false){
            throw new ClientUniqueTaxNumberException();
        }
    }

    /**
     * Sprawdzenie, czy włączona jest weryfikacja unikalności numeru NIP
     * @return bool
     */
    protected function verifyUniqueTaxNumber(): bool
    {
        $config = $this->configService->get(WiseClientExtension::getExtensionAlias());

        if(array_key_exists('verify_client_unique_tax_number', $config)){
            if($config['verify_client_unique_tax_number'] === true){
                return true;
            }
        }

        return false;
    }

    /**
     * Pobranie identyfikatorów grup klienta
     * @param Client $client
     * @return array
     */
    protected function getClientGroupsIds(Client $client): array
    {
        $filters = [];
        $joins = [];

        // Jeśli mam podaną grupe klientów to pobieram wszystkich, którzy należą do tego samego sklepu
        if($client->getClientGroupId() !== null){
            $filters[] = new QueryFilter('id', $client->getClientGroupId());
            $joins[] = new QueryJoin(ClientGroup::class, 'cg', ['storeId' => 'cg.storeId']);
        }

        $params = new CommonListParams();
        $params
            ->setFilters($filters)
            ->setJoins([
                new QueryJoin(ClientGroup::class, 'cg', ['storeId' => 'cg.storeId'])
            ]);

        $clientGroups = ($this->listClientGroupService)($params)->read();

        if(empty($clientGroups)){
            return [];
        }

        return array_unique(array_column($clientGroups, 'id'));
    }
}
