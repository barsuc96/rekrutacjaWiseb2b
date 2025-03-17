<?php

namespace Wise\Agreement\Service\Contract;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Wise\Agreement\Service\Contract\DataProvider\ContractProviderInterface;
use Wise\Agreement\Service\Contract\Interfaces\ContractAdditionalFieldsServiceInterface;
use Wise\Core\Service\AbstractAdditionalFieldsService;

/**
 * Serwis pozwala zwrócić dodatkowe pola dla umowy obsłużone za pomocą provider'ów
 */
class ContractAdditionalFieldsService extends AbstractAdditionalFieldsService implements ContractAdditionalFieldsServiceInterface
{
    protected const PROVIDER_INTERFACE = ContractProviderInterface::class;

    public function __construct(
        #[TaggedIterator('details_provider.contract')] iterable $providers
    ){
        parent::__construct($providers);
    }
}
