<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement;

use Exception;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Helper\Object\ObjectNonModelFieldsHelper;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\User\Domain\Agreement\Agreement;
use Wise\User\Domain\Agreement\AgreementRepositoryInterface;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;
use Wise\User\Service\UserAgreement\Interfaces\ListAllAggrementsForUserServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\UserAgreementAdditionalFieldsServiceInterface;

class ListAllAggrementsForUserService implements ListAllAggrementsForUserServiceInterface
{
    public function __construct(
        private readonly UserAgreementRepositoryInterface $userAgreementRepository,
        private readonly AgreementRepositoryInterface $agreementRepository,
        private readonly UserAgreementAdditionalFieldsServiceInterface $userAgreementDetails
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(
        ListAllAggrementsForUserServiceParams $params
    ): CommonServiceDTO {
        /**
         * Przygotowujemy podstawowe parametry dla listy: limit i page
         */
        $queryParameters = QueryParametersHelper::prepareStandardParameters($params->getFilters());

        /**
         * Dodajemy potrzebne pola do obsłużenia pól dodatkowych
         */
        $fields = $params->getFields();
        if (isset($fields['agreementId']) === false) {
            $fields['agreementId'] = 'agreementId';
        }

        /**
         * TODO Aktualnie nie walidujey czy użytkownik, którego zgody chcemy wyśiwetlić istnieje w bazię:
         * $params->getUserId()
         */

        /**
         * Wykluczamy pole które nie istnieją w modelu biznesowym
         */
        $nonModelFields = ObjectNonModelFieldsHelper::find(UserAgreement::class, $fields);

        $entities = $this->userAgreementRepository->findByQueryFiltersView(
            queryFilters:  $queryParameters->getQueryFilters(),
            orderBy: ['field' => $queryParameters->getSortField(), 'direction' => $queryParameters->getSortDirection()],
            limit: $queryParameters->getLimit(),
            offset: $queryParameters->getOffset(),
            fields: ArrayHelper::removeFieldsInArray($nonModelFields, $fields),
            joins: $params->getJoins()
        );

        $entities ??= [];

        $entities = $this->addNewAgreement($entities);

        $entities = $this->addAdditionalFields($entities, $nonModelFields);

        ($resultDTO = new CommonServiceDTO())->writeAssociativeArray($entities);

        return $resultDTO;
    }

    /**
     * Metoda służy dodaniu nowych zgód, dla listy zgód użytkownika,
     * których użytkownik jeszcze nie akceptował/odrzucał,
     */
    protected function addNewAgreement(array $userAgreements): array
    {
        $agreements = $this->agreementRepository->findBy(['isActive' => true]);

        /** @var Agreement $agreement */
        foreach ($agreements as $agreement) {
            foreach ($userAgreements as $userAgreement) {
                if ($agreement->getId() === (int)$userAgreement['id']) {
                    continue 2;
                }
            }

            $userAgreements[] = [
                'id' => null,
                'agreementId' => $agreement->getId()
            ];
        }

        return $userAgreements;
    }

    /**
     * Metoda służąca do dodania wartości do pól które zostały wcześniej wykluczone, za pomocą Providerów
     *
     * @throws Exception
     */
    protected function addAdditionalFields(array $entities, array $fields): array
    {
        foreach ($entities as $key => $entity) {
            if (isset($entity['id']) === false && $entity['agreementId'] === false) {
                continue;
            }

            foreach ($fields as $field) {
                if ($field === 'type') {
                    /**
                     * TODO Aktualnie pole type nie jest dostepne w modelu biznesowym
                     */
                    $entities[$key][$field] = null;
                    continue;
                }

                $cacheData = [
                    'agreementId' => $entity['agreementId'],
                    'userAgreementId' => $entity['id'] ?? null
                ];

                $entities[$key][$field] = $this->userAgreementDetails->getFieldValue(
                    $entity['id'],
                    $cacheData,
                    $field
                );
            }
        }

        return $entities;
    }
}
