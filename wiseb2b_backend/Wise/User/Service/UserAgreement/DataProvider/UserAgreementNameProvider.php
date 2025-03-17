<?php

namespace Wise\User\Service\UserAgreement\DataProvider;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\UserAgreement\Exceptions\UserAgreementNotFoundException;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;

#[AutoconfigureTag(name: 'details_provider.user_agreement')]
class UserAgreementNameProvider extends AbstractAdditionalFieldProvider implements UserAgreementDetailsProviderInterface
{
    public const FIELD = 'name';

    public function __construct(
    ) {}


    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
//        /** @var UserAgreement $userAgreement */
//        $userAgreement = $this->userAgreementRepository->find($entityId);
//        if(!$userAgreement){
//            throw UserAgreementNotFoundException::id($entityId);
//        }
//
//        if(isset($cacheData['translation']) && isset($cacheData['translation'][$userAgreement->getAgreementId()])){
//            return $cacheData['translation'][$userAgreement->getAgreementId()];
//        }

        return null;
    }
}
