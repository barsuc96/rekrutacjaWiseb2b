<?php

namespace Wise\Agreement\Service\ContractAgreement;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Wise\Agreement\Service\ContractAgreement\DataProvider\ContractAgreementProviderInterface;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ContractAgreementAdditionalFieldsServiceInterface;
use Wise\Core\Service\AbstractAdditionalFieldsService;

/**
 * Serwis pozwala zwrócić dodatkowe pola dla zgody obsłużone za pomocą provider'ów
 */
class ContractAgreementAdditionalFieldsService extends AbstractAdditionalFieldsService implements ContractAgreementAdditionalFieldsServiceInterface
{
    protected const PROVIDER_INTERFACE = ContractAgreementProviderInterface::class;

    public function __construct(
        #[TaggedIterator('details_provider.contract_agreement')] iterable $providers
    ){
        parent::__construct($providers);
    }
}
