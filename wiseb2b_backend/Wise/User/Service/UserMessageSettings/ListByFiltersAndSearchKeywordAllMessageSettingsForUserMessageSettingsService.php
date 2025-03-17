<?php

declare(strict_types=1);

namespace Wise\User\Service\UserMessageSettings;

use Exception;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Helper\Object\ObjectNonModelFieldsHelper;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\Core\Model\QueryFilter;
use Wise\User\Domain\UserMessageSettings\MessageSettings;
use Wise\User\Domain\UserMessageSettings\MessageSettingsRepositoryInterface;
use Wise\User\Domain\UserMessageSettings\MessageSettingsTranslationRepositoryInterface;
use Wise\User\Domain\UserMessageSettings\UserMessageSettings;
use Wise\User\Domain\UserMessageSettings\UserMessageSettingsRepositoryInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsServiceInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\UserMessageSettingsAdditionalFieldsServiceInterface;

/**
 * Serwis aplikacji do pobrania
 */
class ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsService implements
    ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsServiceInterface
{
    public function __construct(
        private readonly UserMessageSettingsRepositoryInterface $repository,
        private readonly MessageSettingsRepositoryInterface $messageSettingsRepository,
        private readonly UserMessageSettingsAdditionalFieldsServiceInterface $userMessageSettingsAdditionalFieldsService,
        private readonly MessageSettingsTranslationRepositoryInterface $messageSettingsTranslationRepository
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(
        ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsParams $params
    ): CommonServiceDTO {
        /**
         * Przygotowujemy podstawowe parametry dla listy: limit i page
         */
        $queryParameters = QueryParametersHelper::prepareStandardParameters($params->getFilters());

        /**
         * Dodajemy potrzebne pola do obsłużenia pól dodatkowych
         *
         * messageSettingsId - pole potrzebne do pobrania nazwy z tłumaczenia dla messageSettings
         */
        $fields = $params->getFields();
        if (isset($fields['messageSettingsId']) === false) {
            $fields['messageSettingsId'] = 'messageSettingsId';
        }

        /**
         * TODO Aktualnie nie walidujey czy użytkownik, którego ustawienia chcemy wyśiwetlić istnieje w bazię:
         * $params->getUserId()
         */

        /**
         * Wykluczamy pole które nie istnieją w modelu biznesowym UserMessageSettings
         */
        $nonModelFileds = ObjectNonModelFieldsHelper::find(UserMessageSettings::class, $fields);

        $entities = $this->repository->findByQueryFiltersView(
            queryFilters:  $queryParameters->getQueryFilters(),
            orderBy: ['field' => $queryParameters->getSortField(), 'direction' => $queryParameters->getSortDirection()],
            limit: $queryParameters->getLimit(),
            offset: $queryParameters->getOffset(),
            fields: ArrayHelper::removeFieldsInArray($nonModelFileds, $fields),
            joins: $params->getJoins()
        );

        $entities ??= [];

        $entities = $this->addNewMessageSettings($entities);

        $entities = $this->addAdditionalFields($entities, $nonModelFileds);

        ($resultDTO = new CommonServiceDTO())->writeAssociativeArray($entities);

        return $resultDTO;
    }

    /**
     * Metoda służy dodaniu nowych ustawień powiadomień, dla listy użytkownika,
     * których użytkownik nie włączył
     */
    protected function addNewMessageSettings(array $userMessagesSettings): array
    {
        $result = [];

        $messagesSettings = $this->messageSettingsRepository->findBy(['isActive' => true]);

        /** @var MessageSettings $messageSettings */
        foreach ($messagesSettings as $messageSettings) {
            $userSettings = $this->findElementByMessageSettingsId($messageSettings->getId(), $userMessagesSettings);

            if($userSettings !== null){
                $result[] = [
                    'id' => $userSettings['id'],
                    'messageSettingsId' => $messageSettings->getId(),
                    'isActive' => $userSettings['isActive'],
                ];
            }else{
                $result[] = [
                    'id' => null,
                    'messageSettingsId' => $messageSettings->getId(),
                    'isActive' => false,
                ];
            }
        }

        return $result;
    }

    /**
     * Metoda służąca do dodania wartości do pól które zostały wcześniej wykluczone, za pomocą Providerów
     *
     * @throws Exception
     */
    protected function addAdditionalFields(array $entities, array $fields): array
    {
        $cache = [];

        $this->getTranslationsForCache($entities, $cache);

        foreach ($entities as $key => $entity) {
            if (isset($entity['id']) === false && $entity['messageSettingsId'] === false) {
                continue;
            }

            foreach ($fields as $field) {
                $cache['messageSettingsId'] = $entity['messageSettingsId'];
                $entities[$key][$field] = $this->userMessageSettingsAdditionalFieldsService->getFieldValue(
                    $entity['id'] ?? null,
                        $cache,
                        $field,
                );
            }
        }

        return $entities;
    }

    protected function findElementByMessageSettingsId(int $messageSettingsId, array $array): ?array {
        foreach ($array as $element) {
            if ($element['messageSettingsId'] == $messageSettingsId) {
                return $element;
            }
        }

        return null;
    }

    /**
     * Metoda służąca do pobrania tłumaczeń dla ustawień powiadomień
     * @param array $entities
     * @param array $cache
     * @return void
     */
    protected function getTranslationsForCache(array $entities, array &$cache): void
    {
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
    }
}
