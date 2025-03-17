<?php

namespace Wise\User\Service\UserMessageSettings;

use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\CommonListParams;
use Wise\User\Domain\UserMessageSettings\MessageSettingsTranslationRepositoryInterface;
use Wise\User\Domain\UserMessageSettings\UserMessageSettings;
use Wise\User\Domain\UserMessageSettings\UserMessageSettingsRepositoryInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\ListUserMessageSettingsServiceInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\UserMessageSettingsAdditionalFieldsServiceInterface;

class ListUserMessageSettingsService extends AbstractListService implements ListUserMessageSettingsServiceInterface
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
    protected function prepareCacheData(array $entities, ?array $nonModelFields, CommonListParams $params): array
    {
        $cache = $params->getDataForCache() ?? [];

        $ids = array_column($entities, 'messageSettingsId');

        if(!empty($ids)){
            $translations = $this->messageSettingsTranslationRepository->findByQueryFiltersView(
                queryFilters: [
                    new QueryFilter('messageSettingsId', array_unique($ids), QueryFilter::COMPARATOR_IN)
                ],
                fields: [
                    'id' => 'id', 'name' => 'name', 'messageSettingsId' => 'messageSettingsId', 'language' => 'language'
                ]
            );
            if(empty($translation)){
                $translationResult = [];
                foreach($translations as $translation){
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
