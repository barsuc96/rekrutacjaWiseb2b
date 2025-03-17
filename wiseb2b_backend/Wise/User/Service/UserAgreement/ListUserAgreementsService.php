<?php

namespace Wise\User\Service\UserAgreement;

use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractListService;
use Wise\Core\Service\CommonListParams;
use Wise\User\Domain\Agreement\AgreementTranslationRepositoryInterface;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;
use Wise\User\Service\UserAgreement\Interfaces\ListUserAgreementsServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\UserAgreementAdditionalFieldsServiceInterface;

class ListUserAgreementsService extends AbstractListService implements ListUserAgreementsServiceInterface
{
    protected const ENTITY_CLASS = UserAgreement::class;

    public function __construct(
        private readonly UserAgreementRepositoryInterface $repository,
        private readonly UserAgreementAdditionalFieldsServiceInterface $additionalFieldsService,
        private readonly AgreementTranslationRepositoryInterface $agreementTranslationRepository
    ){
        parent::__construct($repository, $this->additionalFieldsService);
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

        $ids = array_column($entities, 'agreementId');

        if(!empty($ids)){
            $translations = $this->agreementTranslationRepository->findByQueryFiltersView(
                queryFilters: [
                    new QueryFilter('agreementId', array_unique($ids), QueryFilter::COMPARATOR_IN)
                ],
                fields: [
                    'id' => 'id', 'name' => 'name', 'agreementId' => 'agreementId', 'language' => 'language'
                ]
            );
            if(empty($translation)){
                $translationResult = [];
                foreach($translations as $translation){
                    $translationResult[$translation['agreementId']][] = [
                        'agreementId' => $translation['agreementId'],
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
