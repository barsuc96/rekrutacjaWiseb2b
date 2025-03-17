<?php

namespace Wise\User\ApiUi\Service\Contract;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Agreement\Domain\Contract\Enum\ContractImpact;
use Wise\Agreement\Domain\Contract\Enum\ContractStatus;
use Wise\Agreement\Service\Contract\GetContractsByContextParams;
use Wise\Agreement\Service\Contract\Interfaces\GetContractsByContextServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ListContractAgreementServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetListUiApiService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\ApiUi\Service\Contract\Interfaces\GetUserContractServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;

/**
 * Serwis zwraca zgody, jakie są dostępne dla użytkownika (oraz zawiera informacje, czy już użytkownik ją zaakceptował)
 */
class GetUserContractService extends AbstractGetListUiApiService implements GetUserContractServiceInterface
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

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly ListContractServiceInterface $listContractService,
        private readonly ListContractAgreementServiceInterface $listContractAgreementService,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly GetContractsByContextServiceInterface $getContractsByContextService
    ){
        parent::__construct($sharedActionService, $listContractService);
    }


    /**
     * ## Logika obsługi metody GET LIST
     * @param InputBag $parameters
     * @return array
     * @throws \Exception
     */
    public function get(InputBag $parameters): array
    {
        $params = new GetContractsByContextParams();
        $params
            ->setOnlyMustAccept(false)
            ->setSkipImpact([ContractImpact::ORDER]);

        return ($this->getContractsByContextService)($params)->read();
    }

    /**
     * Metoda umożliwiająca wykonanie pewnej czynności po obsłudze filtrów
     * @param array $filters
     * @param InputBag $parametersAdjusted
     * @return void
     */
    protected function afterInterpretedParameters(array &$filters, InputBag $parametersAdjusted): void
    {
        if(in_array(UserRoleEnum::ROLE_ADMIN->value, $this->currentUserService->getRoles(), true)){
            $filters[] = new QueryFilter('status', [ContractStatus::ACTIVE, ContractStatus::IN_EDIT], QueryFilter::COMPARATOR_IN);
        }else{
            $filters[] = new QueryFilter('status', ContractStatus::ACTIVE);
        }

        // Pomijamy te, które oddziałują na zamówienie
        $filters[] = new QueryFilter('impact', [ContractImpact::ORDER], QueryFilter::COMPARATOR_NOT_IN);
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
            'content' => $this->translationService->getTranslationForField($elementData['content'], $this->localeService->getCurrentLanguage()),
            'testimony' => $this->translationService->getTranslationForField($elementData['testimony'], $this->localeService->getCurrentLanguage()),
            'agreeDate' => $userAgreement['agreeDate'] ?? null,
            'disagreeDate' => $userAgreement['disagreeDate'] ?? null,
            'hasActiveAgree' => !empty($userAgreement['isActive']),
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
                new QueryFilter('userId', $this->currentUserService->getUserId()),
                new QueryFilter('isActive', true)
            ])
            ->setFields([]);

        return ($this->listContractAgreementService)($params)->read();
    }
}
