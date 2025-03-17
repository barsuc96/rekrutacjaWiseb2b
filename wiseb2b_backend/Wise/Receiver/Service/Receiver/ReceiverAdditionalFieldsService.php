<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Wise\Core\Service\AbstractAdditionalFieldsService;
use Wise\Receiver\Service\Receiver\DataProvider\ReceiverDetailsProviderInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverAdditionalFieldsServiceInterface;

class ReceiverAdditionalFieldsService extends AbstractAdditionalFieldsService implements ReceiverAdditionalFieldsServiceInterface
{

    protected const PROVIDER_INTERFACE = ReceiverDetailsProviderInterface::class;

    public function __construct(
        #[TaggedIterator('details_provider.receiver')] iterable $providers
    ){
        parent::__construct($providers);
    }
}
