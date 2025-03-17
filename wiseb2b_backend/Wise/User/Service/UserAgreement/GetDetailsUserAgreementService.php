<?php

namespace Wise\User\Service\UserAgreement;

use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractDetailsService;
use Wise\User\Domain\Agreement\AgreementTranslationRepositoryInterface;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;
use Wise\User\Service\UserAgreement\Interfaces\GetDetailsUserAgreementServiceInterface;

class GetDetailsUserAgreementService extends AbstractDetailsService implements GetDetailsUserAgreementServiceInterface
{
    protected const ENTITY_CLASS = UserAgreement::class;

    public function __construct(
        private readonly UserAgreementRepositoryInterface $repository,
        private readonly UserAgreementAdditionalFieldsService $additionalFieldsService,
        private readonly AgreementTranslationRepositoryInterface $agreementTranslationRepository
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

        if (isset($entity['agreementId'])) {
            $translations = $this->agreementTranslationRepository->findByQueryFiltersView(
                queryFilters: [
                    new QueryFilter('agreementId', $entity['agreementId'])
                ],
                fields: [
                    'id' => 'id',
                    'name' => 'name',
                    'agreementId' => 'agreementId',
                    'language' => 'language'
                ]
            );
            if (empty($translation)) {
                $translationResult = [];
                foreach ($translations as $translation) {
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
