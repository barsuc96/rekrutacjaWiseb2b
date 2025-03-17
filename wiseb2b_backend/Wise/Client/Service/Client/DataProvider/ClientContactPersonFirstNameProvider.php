<?php

namespace Wise\Client\Service\Client\DataProvider;

use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Domain\Client\Exceptions\ClientNotFoundException;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;

class ClientContactPersonFirstNameProvider extends AbstractAdditionalFieldProvider implements ClientDetailsProviderInterface
{
    public const FIELD = 'contactPersonFirstName';

    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
    ){}

    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
        /** @var Client $client */
        $client = $this->clientRepository->find($entityId);

        if(!$client) {
            throw ClientNotFoundException::id($entityId);
        }

        return $client->getClientRepresentative()->getPersonFirstname();
    }
}

