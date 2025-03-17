<?php

namespace Wise\User\Service\UserMessageSettings;

use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractDetailsService;
use Wise\User\Domain\UserMessageSettings\MessageSettings;
use Wise\User\Domain\UserMessageSettings\MessageSettingsRepositoryInterface;
use Wise\User\Domain\UserMessageSettings\MessageSettingsTranslationRepositoryInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\GetDetailsMessageSettingsServiceInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\MessageSettingsAdditionalFieldsServiceInterface;

class GetDetailsMessageSettingsService extends AbstractDetailsService implements GetDetailsMessageSettingsServiceInterface
{
    protected const ENTITY_CLASS = MessageSettings::class;

    protected const AGGREGATES = [];

    public function __construct(
        private readonly MessageSettingsRepositoryInterface $repository,
        private readonly MessageSettingsAdditionalFieldsServiceInterface $additionalFieldsService,
        private readonly MessageSettingsTranslationRepositoryInterface $messageSettingsTranslationRepository
    ){
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
                    new QueryFilter('messageSettingsId', $entity['id'])
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
