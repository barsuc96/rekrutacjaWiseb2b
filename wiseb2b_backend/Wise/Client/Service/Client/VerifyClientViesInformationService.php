<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Client\Service\Client\Interfaces\ModifyClientServiceInterface;
use Wise\Client\Service\Client\Interfaces\VerifyClientViesInformationServiceInterface;
use Wise\Client\Service\Client\Interfaces\ViesValidationServiceInterface;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\Core\Service\CommonListParams;
use Wise\I18n\Service\Country\Interfaces\ListCountriesServiceInterface;

/**
 * Serwis do weryfikacji informacji klienta w VIES
 */
class VerifyClientViesInformationService implements VerifyClientViesInformationServiceInterface
{
    protected const SELLER_COUNTRY_CODE = 'PL';
    public function __construct(
        private readonly ListClientsServiceInterface $listClientsService,
        private readonly ModifyClientServiceInterface $modifyClientService,
        private readonly ViesValidationServiceInterface $viesValidationService,
        private readonly ListCountriesServiceInterface $listCountriesService,
        private readonly RepositoryManagerInterface $repositoryManager
    ){}

    public function __invoke(VerifyClientViesInformationServiceParams $params): void
    {
        $clientsToVerify = $this->getListOfClients($params);
        $europeanUnionCountries = $this->getCountriesOnEuropeanUnion();

        foreach ($clientsToVerify as $client){
            $isViesValid = false;

            if(!empty($client['taxNumber']) && !empty($client['registerAddress']) && !empty($client['registerAddress']['countryCode']) && $client['registerAddress']['countryCode'] != self::SELLER_COUNTRY_CODE){

                // Czy kraj znajduje się na liście krajów UE
                if(in_array(strtoupper($client['registerAddress']['countryCode']), $europeanUnionCountries)){

                    // Upewniam się, że na początku nipu znajduje się kod kraju
                    if (!preg_match('/^[A-Za-z]{2}[A-Za-z0-9]+$/', $client['taxNumber'])) {
                        $client['taxNumber'] = $client['registerAddress']['countryCode'] . $client['taxNumber'];
                    }

                    // Weryfikacja numeru VAT UE
                    $params = new ViesValidationServiceParams();
                    $params->setTaxNumber($client['taxNumber']);

                    $isViesValid = ($this->viesValidationService)($params);
                }
            }

            // Aktualizacja informacji o weryfikacji
            $this->updateClient($client['id'], $isViesValid);
            $this->repositoryManager->flush();
        }
    }

    /**
     * Zwraca listę klientów
     * @return array
     */
    protected function getListOfClients(VerifyClientViesInformationServiceParams $params): array
    {
        $filters = [
            new QueryFilter('limit', null)
        ];

        if($params->getClientId() !== null){
            $filters[] = new QueryFilter('id', $params->getClientId());
        }

        $params = new CommonListParams();
        $params
            ->setFilters($filters)
            ->setFields([
                'id' => 'id',
                'taxNumber' => 'taxNumber',
                'registerAddress' => 'registerAddress',
            ]);

        $clients = ($this->listClientsService)($params)->read();

        return $clients;
    }

    /**
     * Zwraca listę krajów UE
     * @return array
     */
    protected function getCountriesOnEuropeanUnion(): array
    {
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('limit', null),
                new QueryFilter('inEuropeanUnion', true),
            ])
            ->setFields([
                'id' => 'id',
                'idExternal' => 'idExternal',
                'inEuropeanUnion' => 'inEuropeanUnion',
            ]);

        $countries = ($this->listCountriesService)($params)->read();
        foreach ($countries as &$country){
            $country = strtoupper($country['idExternal']);
        }

        return $countries;
    }

    /**
     * Aktualizacja informacji o weryfikacji klienta
     * @param int $clientId
     * @param bool $isViesValid
     * @return void
     */
    private function updateClient(int $clientId, bool $isViesValid): void
    {
        $params = new CommonModifyParams();
        $params
            ->writeAssociativeArray([
                'id' => $clientId,
                'isVies' => $isViesValid,
                'viesLastUpdate' => new \DateTime(),
            ]);

        ($this->modifyClientService)($params);
    }
}
