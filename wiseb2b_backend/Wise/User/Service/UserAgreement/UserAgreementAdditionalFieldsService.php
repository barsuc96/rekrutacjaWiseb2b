<?php

namespace Wise\User\Service\UserAgreement;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Wise\Core\Service\AbstractAdditionalFieldsService;
use Wise\User\Service\UserAgreement\DataProvider\UserAgreementDetailsProviderInterface;
use Wise\User\Service\UserAgreement\Interfaces\UserAgreementAdditionalFieldsServiceInterface;

class UserAgreementAdditionalFieldsService extends AbstractAdditionalFieldsService implements UserAgreementAdditionalFieldsServiceInterface
{
    protected const PROVIDER_INTERFACE = UserAgreementDetailsProviderInterface::class;

    public function __construct(
        #[TaggedIterator('details_provider.user_agreement')] iterable $providers
    ){
        parent::__construct($providers);
    }
}
