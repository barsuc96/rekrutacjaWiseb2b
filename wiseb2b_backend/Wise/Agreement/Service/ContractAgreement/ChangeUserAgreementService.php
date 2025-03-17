<?php

namespace Wise\Agreement\Service\ContractAgreement;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Wise\Agreement\Domain\Contract\Enum\ContractImpact;
use Wise\Agreement\Domain\Contract\Enum\ContractRequirement;
use Wise\Agreement\Domain\Contract\Enum\ContractStatus;
use Wise\Agreement\Domain\Contract\Exception\ContractNotFoundException;
use Wise\Agreement\Service\Contract\Interfaces\ChangeUserAgreementServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\GetContractDetailsServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\AddOrModifyContractAgreementServiceInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\GetContractAgreementDetailsServiceInterface;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\Agreement\Exceptions\AgreementRequiredException;

class ChangeUserAgreementService implements ChangeUserAgreementServiceInterface
{
    public function __construct(
        private readonly GetContractDetailsServiceInterface $contractDetailsService,
        private readonly GetContractAgreementDetailsServiceInterface $getContractAgreementDetailsService,
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly RequestStack $requestStack,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService,
        private readonly AddOrModifyContractAgreementServiceInterface $addOrModifyContractAgreementService
    ){}


    public function __invoke(ChangeUserAgreementParams $params): CommonServiceDTO
    {
        // Pobranie danych o umowie i już wyrażonej wcześniej zgodzie
        $contract = $this->findContract($params);

        if($contract === null){
            // Jeśli zgoda nie istniała a podano odmowe zgody
            if($params->getType() == ChangeUserAgreementParams::TYPE_DISAGREE){
                return new CommonServiceDTO();
            }

            // Jeśli zgoda nie istniała a podano wyrażenie zgody
            throw new ContractNotFoundException();
        }

        $contractAgreement = $this->findContractAgreement($params, $contract);

        // Obsługa zgód
        $resultData = $this->handleAgreement($params, $contractAgreement, $contract);

        $result = new CommonServiceDTO();
        $result->writeAssociativeArray($resultData);

        return $result;
    }

    /**
     * Weryfikacja, czy zgoda jest wymagana i czy została zaakceptowana
     * @param array|null $agreementData
     * @param bool $isGranted
     * @return void
     * @throws ExceptionInterface
     */
    protected function checkAgreement(?array $agreementData, bool $isGranted): void
    {
        if($agreementData['isRequired'] === true && $isGranted === false){
            throw AgreementRequiredException::content($this->translationService->getTranslationForField($agreementData['description'], $this->localeService->getCurrentLanguage()));
        }
    }


    /**
     * Znajduje zgode na podstawie przekazanych parametrow
     * @param ChangeUserAgreementParams $agreementParams
     * @return array|null
     */
    protected function findContract(ChangeUserAgreementParams $agreementParams): ?array
    {
        $agreementDetails = null;

        // Wyszukujemy na podstawie identyfikatora umowy
        if($agreementParams->getContractId() !== null) {
            $params = new CommonDetailsParams();
            $params
                ->setId($agreementParams->getContractId())
                ->setFields(['id' => 'id', 'status' => 'status', 'impact' => 'impact', 'requirement' => 'requirement'])
                ->setExecuteExceptionWhenEntityNotExists(false);

            $agreementDetails = ($this->contractDetailsService)($params)->read();
        }

        // Szukamy na podstawie typu umowy
        if(empty($agreementDetails) && !empty($agreementParams->getType())){
            $params = new CommonDetailsParams();
            $params
                ->setFilters([
                    new QueryFilter('type', $agreementParams->getType())
                ])
                ->setFields(['id' => 'id', 'status' => 'status', 'impact' => 'impact'])
                ->setExecuteExceptionWhenEntityNotExists(false);

            $agreementDetails = ($this->contractDetailsService)($params)->read();
        }

        if(!empty($agreementDetails)){
            return $agreementDetails;
        }

        return null;
    }

    /**
     * Przygotowuje dane dotyczące zgody
     * @param array $data
     * @return void
     */
    protected function prepareAgreeAndDisagreeData(array &$data): void
    {
        $isGranted = $data['isActive'];

        if($isGranted) {
            $data['agreeDate'] = new \DateTime();
            $data['agreeIp'] = $this->getUserIp();
        } else {
            $data['agreeDate'] = new \DateTime();
            $data['disagreeIp'] = $this->getUserIp();
        }
    }


    /**
     * Zwraca Ip użytkownika
     * @return string|null
     */
    protected function getUserIp(): ?string
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        return $currentRequest?->getClientIp();
    }

    /**
     * Znajduje zgode na podstawie przekazanych parametrow
     * @param ChangeUserAgreementParams $params
     * @param array $contract
     * @return array
     */
    protected function findContractAgreement(ChangeUserAgreementParams $params, array $contract): array
    {
        $contractAgreement = null;

        if($params->getContractId() !== null || $params->getUserId() !== null) {
            $filters = [
                new QueryFilter('contractId', $params->getContractId())
            ];

            if($contract['impact'] == ContractImpact::USER){
                $filters[] = new QueryFilter('userId', $params->getUserId());
            }

            if($contract['impact'] == ContractImpact::CLIENT){
                $filters[] = new QueryFilter('clientId', $params->getClientId());
            }

            if($contract['impact'] == ContractImpact::ORDER){
                $filters[] = new QueryFilter('cartId', $params->getCartId());
            }

            $contractAgreementParams = new CommonDetailsParams();
            $contractAgreementParams
                ->setFilters($filters)
                ->setFields([])
                ->setExecuteExceptionWhenEntityNotExists(false);

            $contractAgreement = ($this->getContractAgreementDetailsService)($contractAgreementParams)->read();
        }

        return $contractAgreement;
    }


    /**
     * Obsługa zgód
     * @param ChangeUserAgreementParams $params
     * @param array|null $contractAgreement
     * @param array|null $contract
     * @return array|null
     */
    protected function handleAgreement(ChangeUserAgreementParams $params, ?array $contractAgreement, ?array $contract)
    {
        $type = $this->prepareType($params, $contractAgreement);

        if($type === ChangeUserAgreementParams::TYPE_DISAGREE){
            return $this->handleDisagree($params, $contractAgreement, $contract);
        }

        return $this->handleAgree($params, $contractAgreement, $contract);
    }

    /**
     * Obsługa wyrażenie odmowy zgody
     * @param ChangeUserAgreementParams $params
     * @param array|null $contractAgreement
     * @param array|null $contract
     * @return array
     */
    protected function handleDisagree(ChangeUserAgreementParams $params, ?array $contractAgreement, ?array $contract): array
    {
        if(!$params->isCanDisagreeRequiredContract() && $contract['requirement'] !== ContractRequirement::VOLUNTARY && $params->isSkipRequirementValidation() === false){
            throw (new CommonLogicException())->setTranslation('exceptions.contract_agreement.disagree_contract_not_voluntary');
        }

        if($contractAgreement == null){
            throw (new CommonLogicException())->setTranslation('exceptions.contract_agreement.first_must_accept');
        }

        if(!in_array($contract['status'], [ContractStatus::ACTIVE, ContractStatus::DEPRECATED])){
            throw (new CommonLogicException())->setTranslation('exceptions.contract_agreement.disagree_contract_not_active');
        }

        $contractAgreementParams = new CommonModifyParams();
        $contractAgreementParams
            ->writeAssociativeArray([
                'id' => $contractAgreement['id'] ?? null,
                'userId' => $this->impactElement($params, $contract, ContractImpact::USER),
                'clientId' => $this->impactElement($params, $contract, ContractImpact::CLIENT),
                'cartId' => $this->impactElement($params, $contract, ContractImpact::ORDER),
                'contractId' => $params->getContractId() ?? $contract['id'] ?? null,
                'disagreeDate' => new \DateTime(),
                'disagreeIp' => $this->getUserIp(),
                'isActive' => false,
            ]);

        return ($this->addOrModifyContractAgreementService)($contractAgreementParams)->read();
    }

    /**
     * Obsługa wyrażenie zgody
     * @param ChangeUserAgreementParams $params
     * @param array|null $contractAgreement
     * @param array|null $contract
     * @return array
     */
    protected function handleAgree(ChangeUserAgreementParams $params, ?array $contractAgreement, ?array $contract): array
    {
        if(!in_array($contract['status'], [ContractStatus::ACTIVE, ContractStatus::DEPRECATED])){
            throw (new CommonLogicException())->setTranslation('exceptions.contract_agreement.agree_contract_not_active');
        }

        $contractAgreementParams = new CommonModifyParams();
        $contractAgreementParams
            ->writeAssociativeArray([
                'isActive' => true,
                'userId' => $this->impactElement($params, $contract, ContractImpact::USER),
                'clientId' => $this->impactElement($params, $contract, ContractImpact::CLIENT),
                'cartId' => $this->impactElement($params, $contract, ContractImpact::ORDER),
                'contractId' => $params->getContractId() ?? $contract['id'] ?? null,
                'agreeDate' => new \DateTime(),
                'agreeIp' => $this->getUserIp(),
                'disagreeDate' => null,
                'disagreeIp' => null,
                'contextAgreement' => $params->getContextAgreement(),
                'id' => $contractAgreement['id'] ?? null,
            ]);

        return ($this->addOrModifyContractAgreementService)($contractAgreementParams)->read();
    }

    /**
     * Zwraca identyfikator elementu, na którym ma być wykonana operacja
     * @param ChangeUserAgreementParams $params
     * @param array|null $contract
     * @param int $type
     * @return int|null
     */
    protected function impactElement(ChangeUserAgreementParams $params, ?array $contract, int $type): ?int
    {
        if($type == ContractImpact::USER && $contract['impact'] == ContractImpact::USER){
            return $params->getUserId();
        }

        if($type == ContractImpact::CLIENT && $contract['impact'] == ContractImpact::CLIENT){
            return $params->getClientId();
        }

        if($type == ContractImpact::ORDER && $contract['impact'] == ContractImpact::ORDER){
            if($params->getCartId() === null){
                throw (new CommonLogicException())->setTranslation('exceptions.contract_agreement.cart_id_required');
            }

            return $params->getCartId();
        }

        return null;
    }

    protected function prepareType(ChangeUserAgreementParams $params, ?array $contractAgreement): string
    {
        if($params->getType() === ChangeUserAgreementParams::TYPE_TOGGLE){
            if(array_key_exists('isActive', $contractAgreement)){
                return $contractAgreement['isActive'] ? ChangeUserAgreementParams::TYPE_DISAGREE : ChangeUserAgreementParams::TYPE_AGREE;
            }
        }

        if($params->getType() !== null){
            return $params->getType();
        }

        return ChangeUserAgreementParams::TYPE_AGREE;
    }
}
