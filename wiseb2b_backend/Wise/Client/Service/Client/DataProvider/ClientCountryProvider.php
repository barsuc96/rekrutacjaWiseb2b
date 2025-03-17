<?php

namespace Wise\Client\Service\Client\DataProvider;

use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Domain\Client\ClientServiceInterface;
use Wise\Client\Domain\Client\Exceptions\ClientNotFoundException;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\Core\Exception\CoreEntityExceptions\GlobalAddressNotFoundException;
use Wise\Core\Repository\Doctrine\GlobalAddress;
use Wise\Core\Repository\Doctrine\GlobalAddressRepositoryInterface;

class ClientCountryProvider extends AbstractAdditionalFieldProvider implements ClientDetailsProviderInterface
{
    public const FIELD = 'country';

    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly GlobalAddressRepositoryInterface $globalAddressRepository,
        private readonly ClientServiceInterface $clientService
    ){}

    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
        /** @var Client $client */
        $client = $this->clientRepository->find($entityId);

        if(!$client) {
            throw ClientNotFoundException::id($entityId);
        }

        /** @var GlobalAddress $address */
        $address = $this->globalAddressRepository->getGlobalAddress(
            $this->clientService->getCurrentEntityName(),
            $this->clientRepository->getRegisterAddressEntityFieldName(),
            $client->getId()
        );

        if(empty($address)){
            throw new GlobalAddressNotFoundException();
        }

        return $address['countryCode'];
    }
}
