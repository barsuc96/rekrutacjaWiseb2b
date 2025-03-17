<?php

namespace Wise\Client\Service\ClientGroup;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Wise\Client\Service\ClientGroup\DataProvider\ClientGroupDetailsProviderInterface;
use Wise\Client\Service\ClientGroup\Interfaces\ClientGroupAdditionalFieldsServiceInterface;
use Wise\Core\Service\AbstractAdditionalFieldsService;

class ClientGroupAdditionalFieldsService extends AbstractAdditionalFieldsService implements ClientGroupAdditionalFieldsServiceInterface
{
    protected const PROVIDER_INTERFACE = ClientGroupDetailsProviderInterface::class;

    public function __construct(
        #[TaggedIterator('details_provider.client_group')] iterable $providers
    ){
        parent::__construct($providers);
    }
}
