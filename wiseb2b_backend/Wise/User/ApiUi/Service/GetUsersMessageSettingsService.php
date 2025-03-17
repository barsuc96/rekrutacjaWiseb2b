<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\User\ApiUi\Dto\Users\UserMessageSettingsResponseDto;
use Wise\User\ApiUi\Service\Interfaces\GetUsersMessageSettingsServiceInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsServiceInterface;
use Wise\User\Service\UserMessageSettings\ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsParams;

class GetUsersMessageSettingsService extends AbstractGetService implements GetUsersMessageSettingsServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsServiceInterface $service,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService
    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        /**
         * Dodajemy filtr po użytkowniku którego dane mamy wyświetlić
         */
        $userId = $parameters->get('userId') ? (int)$parameters->get('userId') : -1;

        $filters = [
            new QueryFilter('userId', $userId)
        ];

        $joins = [];

        foreach ($parameters->all() as $field => $value) {
            if ($field === 'contentLanguage') {
                continue;
            }

            $filters[] = new QueryFilter($field, $value);
        }

        $fields = [
            'enabled' => 'isActive'
        ];

        $fields = (new UserMessageSettingsResponseDto())->mergeWithMappedFields($fields);

        //Przekazanie parametrów do serwisu
        $params = new ListByFiltersAndSearchKeywordAllMessageSettingsForUserMessageSettingsParams();

        $params
            ->setFilters($filters)
            ->setJoins($joins)
            ->setFields($fields)
            ->setUserId($userId)
        ;

        $serviceDtoData = ($this->service)($params)->read();

        $this->fillName($serviceDtoData);

        return (new UserMessageSettingsResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }

    /**
     * Uzupełnia tłumaczenia dla nazw
     * @param array|null $serviceDtoData
     * @return void
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    protected function fillName(?array &$serviceDtoData): void
    {
        foreach ($serviceDtoData as &$data){
            if(isset($data['name'])){
                $data['name'] = $this->translationService->getTranslationForField($data['name'], $this->localeService->getCurrentLanguage());
            }
        }
    }
}
