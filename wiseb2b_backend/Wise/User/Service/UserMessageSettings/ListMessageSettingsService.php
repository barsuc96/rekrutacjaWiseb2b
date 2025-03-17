<?php

namespace Wise\User\Service\UserMessageSettings;

use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\CommonListParams;
use Wise\User\Domain\UserMessageSettings\MessageSettings;
use Wise\User\Domain\UserMessageSettings\MessageSettingsRepositoryInterface;
use Wise\User\Domain\UserMessageSettings\MessageSettingsTranslationRepositoryInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\ListMessageSettingsServiceInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\MessageSettingsAdditionalFieldsServiceInterface;

class ListMessageSettingsService extends AbstractListService implements ListMessageSettingsServiceInterface
{
    protected const ENTITY_CLASS = MessageSettings::class;

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
    protected function prepareCacheData(array $entities, ?array $nonModelFields, CommonListParams $params): array
    {
        $cache = $params->getDataForCache() ?? [];

        $ids = array_column($entities, 'id');

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
