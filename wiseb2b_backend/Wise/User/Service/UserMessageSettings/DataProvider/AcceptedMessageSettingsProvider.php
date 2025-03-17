<?php

namespace Wise\User\Service\UserMessageSettings\DataProvider;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\UserMessageSettings\Exceptions\MessageSettingsNotFoundException;
use Wise\User\Domain\UserMessageSettings\MessageSettingsRepositoryInterface;
use Wise\User\Domain\UserMessageSettings\UserMessageSettings;
use Wise\User\Domain\UserMessageSettings\UserMessageSettingsRepositoryInterface;

#[AutoconfigureTag(name: 'details_provider.message_settings')]
class AcceptedMessageSettingsProvider extends AbstractAdditionalFieldProvider implements MessageSettingsDetailsProviderInterface
{
    public const FIELD = 'accepted';

    public function __construct(
        private readonly MessageSettingsRepositoryInterface $messageSettingsRepository,
        private readonly UserMessageSettingsRepositoryInterface $userMessageSettingsRepository,
    ) {}

    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
        $messageSettings = $this->messageSettingsRepository->find($entityId);
        if(!$messageSettings) {
            throw MessageSettingsNotFoundException::id($entityId);
        }

        $result = false;

        if(isset($cacheData['clientId'])){
            /** @var UserMessageSettings $userMessageSettings */
           $userMessageSettings =  $this->userMessageSettingsRepository->findBy(['clientId' => $cacheData['clientId'], 'messageSettingsId' => $entityId]);
            if($userMessageSettings && $userMessageSettings->getIsActive()){
                $result = true;
            }
        }

        if($result == false && isset($cacheData['userId'])){
            /** @var UserMessageSettings $userMessageSettings */
            $userMessageSettings =  $this->userMessageSettingsRepository->findBy(['userId' => $cacheData['userId'], 'messageSettingsId' => $entityId]);
            if($userMessageSettings && $userMessageSettings->getIsActive()){
                $result = true;
            }
        }

        return $result;
    }
}
