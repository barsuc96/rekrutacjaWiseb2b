<?php

namespace Wise\User\ApiUi\Service\PanelManagement\Users;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ListContractAgreementServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\GetPanelManagementUserAgreementServiceInterface;

class GetPanelManagementUserAgreementService extends AbstractGetListUiApiService implements GetPanelManagementUserAgreementServiceInterface
{
    /**
     * Czy pobrać ilość wszystkich rekordów
     */
    protected bool $fetchTotal = true;

    /**
     * Tablica ze zgodami użytkownika
     * @var array
     */
    protected array $userAgreements = [];

    protected ?int $userId = null;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListContractServiceInterface $listContractService,
        private readonly ListContractAgreementServiceInterface $listContractAgreementService,
        private readonly TranslatorInterface $translator,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
    ){
        parent::__construct($sharedActionService, $listContractService);
    }

    /**
     * Metoda umożliwiająca wykonanie pewnej czynności przed obsługą filtrów
     * @param InputBag $parametersAdjusted
     * @return void
     */
    protected function beforeInterpretParameters(InputBag $parametersAdjusted): void
    {
        $this->userId = $parametersAdjusted->getInt('userId');
        $parametersAdjusted->remove('userId');
    }

    /**
     * Metoda pozwala na dodanie własnych filtrów do listy filtrów
     * Zwraca wartość bool wskazującą, czy dalsze przetwarzanie bieżącego pola powinno być kontynuowane.
     * Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     * @param array $filters Referencja do tablicy filtrów, do której można dodać własne filtry.
     * @param int|string $field Nazwa parametru do przetworzenia.
     * @param mixed $value Wartość parametru do przetworzenia.
     * @return bool Wartość true wykonuje "continue" w pętli przetwarzającej parametry.
     * @example Wise\Order\ApiUi\Service\Orders\GetOrdersService
     */
    protected function customInterpreterParameters(array &$filters, int|string $field, mixed $value): bool
    {
        if($field === 'status'){
            $filters[] = new QueryFilter('status', $value);
            return true;
        }

        if($field === 'context'){
            $this->temporaryData['searchKeyword'] = $value;
            return true;
        }

        return false;
    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        $this->userAgreements = $this->listUserAgreements();
        parent::prepareServiceDtoBeforeTransform($serviceDtoData);
    }


    /**
     * Metoda pozwala przekształcić poszczególne obiekty serviceDto przed transformacją do responseDto
     * @param array|null $elementData
     * @return void
     * @throws ExceptionInterface
     */
    protected function prepareElementServiceDtoBeforeTransform(?array &$elementData): void
    {
        $userAgreement = array_filter($this->userAgreements, function($agreement) use ($elementData){
            return $agreement['contractId'] === $elementData['id'];
        });

        if(!empty($userAgreement)){
            $userAgreement = reset($userAgreement);
        }

        $elementData = [
            ...$elementData,
            'name' => $this->translationService->getTranslationForField($elementData['name'], $this->localeService->getCurrentLanguage()),
            'content' => $this->translationService->getTranslationForField($elementData['content'], $this->localeService->getCurrentLanguage()),
            'agreeIp' => $userAgreement['agreeIp'] ?? null,
            'agreeDate' => $userAgreement['agreeDate'] ?? null,
            'disagreeIp' => $userAgreement['disagreeIp'] ?? null,
            'disagreeDate' => $userAgreement['disagreeDate'] ?? null,
            'hasActiveAgree' => !empty($userAgreement['isActive'])
        ];
    }

    /**
     * Zwraca listę zgód użytkownika
     * @return array
     */
    protected function listUserAgreements(): array
    {
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('userId', $this->userId),
                new QueryFilter('isActive', true)
            ])
            ->setFields([]);

        return ($this->listContractAgreementService)($params)->read();
    }
}

