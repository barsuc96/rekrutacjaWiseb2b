<?php

namespace Wise\User\Service\UserMessageSettings;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Wise\Core\Service\AbstractAdditionalFieldsService;
use Wise\User\Service\UserMessageSettings\DataProvider\MessageSettingsDetailsProviderInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\MessageSettingsAdditionalFieldsServiceInterface;

class MessageSettingsAdditionalFieldsService extends AbstractAdditionalFieldsService implements MessageSettingsAdditionalFieldsServiceInterface
{
    protected const PROVIDER_INTERFACE = MessageSettingsDetailsProviderInterface::class;

    public function __construct(
        #[TaggedIterator('details_provider.message_settings')] iterable $providers
    ){
        parent::__construct($providers);
    }
}
