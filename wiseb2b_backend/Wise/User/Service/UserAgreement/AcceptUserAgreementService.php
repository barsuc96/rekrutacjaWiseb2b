<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement;

use Symfony\Component\HttpFoundation\RequestStack;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\Agreement\Exceptions\AgreementNotFoundException;
use Wise\User\Domain\Agreement\Exceptions\AgreementRequiredException;
use Wise\User\Service\Agreement\Interfaces\GetAgreementDetailsServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\AcceptUserAgreementServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\AddOrModifyUserAgreementServiceInterface;

/**
 * Akceptacja zgody
 */
class AcceptUserAgreementService implements AcceptUserAgreementServiceInterface
{

    public function __construct(
        protected readonly AddOrModifyUserAgreementServiceInterface $addOrModifyUserAgreementService,
        private readonly GetAgreementDetailsServiceInterface $getAgreementDetailsService,
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly RequestStack $requestStack,
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService
    ) {}

    public function __invoke(AcceptUserAgreementParams $params): CommonServiceDTO
    {
        $agreement = $this->findAgreement($params);
        $this->checkAgreement($agreement, $params->isGranted());

        $acceptData = [
            'userId' => $params->getUserId(),
            'clientId' => $params->getClientId(),
            'agreementId' => $agreement['id'],
            'isActive' => $params->isGranted()
        ];

        $this->prepareAgreeAndDisagreeData($acceptData);

        $paramsModify = new CommonModifyParams();
        $paramsModify->writeAssociativeArray($acceptData);

        return ($this->addOrModifyUserAgreementService)($paramsModify);
    }

    /**
     * Weryfikacja, czy zgoda jest wymagana i czy została zaakceptowana
     * @param array|null $agreementData
     * @param bool $isGranted
     * @return void
     */
    protected function checkAgreement(?array $agreementData, bool $isGranted): void
    {
        if($agreementData['isRequired'] === true && $isGranted === false){
            throw AgreementRequiredException::content($this->translationService->getTranslationForField($agreementData['description'], $this->localeService->getCurrentLanguage()));
        }
    }

    /**
     * Znajduje zgode na podstawie przekazanych parametrow
     * @param AcceptUserAgreementParams $agreementParams
     * @return array
     */
    protected function findAgreement(AcceptUserAgreementParams $agreementParams): array
    {
        $agreementDetails = null;

        if($agreementParams->getAgreementId() !== null) {
            $params = new CommonDetailsParams();
            $params
                ->setId($agreementParams->getAgreementId())
                ->setFields(['id' => 'id', 'isRequired' => 'isRequired']);

            $agreementDetails = ($this->getAgreementDetailsService)($params)->read();
        }

        if(empty($agreementDetails) && $agreementParams->getAgreementSymbol() !== null){
            $params = new CommonDetailsParams();
            $params
                ->setFilters([
                    new QueryFilter('symbol', $agreementParams->getAgreementSymbol())
                ])
                ->setFields(['id' => 'id', 'isRequired' => 'isRequired']);

            $agreementDetails = ($this->getAgreementDetailsService)($params)->read();
        }

        if(!empty($agreementDetails)){
            return $agreementDetails;
        }

        throw new AgreementNotFoundException();
    }

    /**
     * Przygotowuje dane dotyczące zgody
     * @param array $acceptData
     * @return void
     */
    protected function prepareAgreeAndDisagreeData(array &$acceptData): void
    {
        $isGranted = $acceptData['isActive'];

        if($isGranted) {
            $acceptData['agreeDate'] = new \DateTime();
            $acceptData['agreeIp'] = $this->getUserIp();
        } else {
            $acceptData['agreeDate'] = new \DateTime();
            $acceptData['disagreeIp'] = $this->getUserIp();
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
}
