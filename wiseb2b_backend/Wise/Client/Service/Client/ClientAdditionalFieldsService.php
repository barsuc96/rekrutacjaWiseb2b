<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Wise\Client\Service\Client\DataProvider\ClientDetailsProviderInterface;
use Wise\Core\Service\AbstractAdditionalFieldsService;

class ClientAdditionalFieldsService extends AbstractAdditionalFieldsService
{
    protected const PROVIDER_INTERFACE = ClientDetailsProviderInterface::class;

    public function __construct(
        #[TaggedIterator('details_provider.client')] iterable $providers
    ){
        parent::__construct($providers);
    }
}
