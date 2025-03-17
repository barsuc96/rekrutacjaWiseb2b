<?php

namespace Wise\User\Service\UserMessageSettings;

use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractDetailsService;
use Wise\User\Domain\UserMessageSettings\MessageSettingsTranslationRepositoryInterface;
use Wise\User\Domain\UserMessageSettings\UserMessageSettings;
use Wise\User\Domain\UserMessageSettings\UserMessageSettingsRepositoryInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\GetDetailsUserMessageSettingsServiceInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\UserMessageSettingsAdditionalFieldsServiceInterface;

class GetDetailsUserMessageSettingsService extends AbstractDetailsService implements GetDetailsUserMessageSettingsServiceInterface
{
    protected const ENTITY_CLASS = UserMessageSettings::class;

    public function __construct(
        private readonly UserMessageSettingsRepositoryInterface $repository,
        private readonly UserMessageSettingsAdditionalFieldsServiceInterface $additionalFieldsService,
        private readonly MessageSettingsTranslationRepositoryInterface $messageSettingsTranslationRepository
    ) {
        parent::__construct($repository, $additionalFieldsService);
    }

    /**
     * Przygotowuje dane do cache
     * @param array $entity
     * @param array|null $dateToCache
     * @return array
     */
    protected function prepareCacheData(array $entity, array $nonModelFields, ?array $dateToCache): array
    {
        $cache = $dateToCache ?? [];

        if (isset($entity['id'])) {
            $translations = $this->messageSettingsTranslationRepository->findByQueryFiltersView(
                queryFilters: [
                    new QueryFilter('messageSettingsId', $entity['messageSettingsId'])
                ],
                fields: [
                    'id' => 'id',
                    'name' => 'name',
                    'messageSettingsId' => 'messageSettingsId',
                    'language' => 'language'
                ]
            );
            if (empty($translation)) {
                $translationResult = [];
                foreach ($translations as $translation) {
                    $translationResult[$translation['messageSettingsId']][] = [
                        'messageSettingsId' => $translation['messageSettingsId'],
                        'language' => $translation['language'],
                        'translation' => $translation['name']
                    ];
                }

                $cache['translation'] = $translationResult;
            }
        }

        return $cache;
    }
}
