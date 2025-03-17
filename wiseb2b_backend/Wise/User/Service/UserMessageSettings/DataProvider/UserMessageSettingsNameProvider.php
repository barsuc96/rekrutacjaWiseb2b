<?php

declare(strict_types=1);

namespace Wise\User\Service\UserMessageSettings\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\UserMessageSettings\Exceptions\UserMessageSettingsNotFoundException;
use Wise\User\Domain\UserMessageSettings\MessageSettingsTranslation;
use Wise\User\Domain\UserMessageSettings\MessageSettingsTranslationRepositoryInterface;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\User\Domain\UserMessageSettings\UserMessageSettings;
use Wise\User\Domain\UserMessageSettings\UserMessageSettingsRepositoryInterface;

/**
 * Provider do pobrania nazwy powiadomień na podstawie aktualnego ustawionego języka dla użytkownika
 */
#[AutoconfigureTag(name: 'details_provider.user_message_settings')]
class UserMessageSettingsNameProvider extends AbstractAdditionalFieldProvider implements UserMessageSettingsDetailsProviderInterface
{
    public const FIELD = 'name';

    public function __construct(
        private readonly MessageSettingsTranslationRepositoryInterface $repository,
        private readonly LocaleServiceInterface $localeService,
        private readonly UserMessageSettingsRepositoryInterface $userMessageSettingsRepository,
    ) {}

    /**
     * Pobieramy tłumaczenie dla pola name, z powiadomienia
     *
     * @throws Exception
     */
    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
        if($entityId === null && !isset($cacheData['messageSettingsId'])){
            return null;
        }

        if($entityId !== null){
            /** @var UserMessageSettings $userMessageSettings */
            $userMessageSettings = $this->userMessageSettingsRepository->find($entityId);
            $messageSettingsId = $userMessageSettings?->getMessageSettingsId() ?? null;
        } else {
            $messageSettingsId = $cacheData['messageSettingsId'] ?? null;
        }

        if(isset($cacheData['translation']) && isset($cacheData['translation'][$messageSettingsId])){
            return $cacheData['translation'][$messageSettingsId];
        }

        return null;
    }
}
