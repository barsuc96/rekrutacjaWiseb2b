<?php

namespace Wise\User\Service\UserMessageSettings;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Wise\Core\Service\AbstractAdditionalFieldsService;
use Wise\User\Service\UserMessageSettings\DataProvider\UserMessageSettingsDetailsProviderInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\UserMessageSettingsAdditionalFieldsServiceInterface;

class UserMessageSettingsAdditionalFieldsService extends AbstractAdditionalFieldsService implements UserMessageSettingsAdditionalFieldsServiceInterface
{
    protected const PROVIDER_INTERFACE = UserMessageSettingsDetailsProviderInterface::class;

    public function __construct(
        #[TaggedIterator('details_provider.user_message_settings')] iterable $providers
    ){
        parent::__construct($providers);
    }
}
