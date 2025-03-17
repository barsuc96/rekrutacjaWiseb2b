<?php

namespace Wise\Agreement\Domain\ContractTypeDictionary\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Wise\Agreement\Domain\ContractTypeDictionary\ContractTypeDictionaryRepositoryInterface;
use Wise\Agreement\Domain\ContractTypeDictionary\Event\ContractTypeDictionaryBeforeRemoveEvent;
use Wise\Agreement\Domain\ContractTypeDictionary\Exception\RemoveContractTypeOnActiveAgreementsException;

#[AsEventListener(event: 'contract_type_dictionary.before.remove')]
class BlockRemoveContractTypeWhenContractHavaActiveAgreementsListener
{

    public function __construct(
        private readonly ContractTypeDictionaryRepositoryInterface $contractTypeDictionaryRepository
    ){}

    public function __invoke(ContractTypeDictionaryBeforeRemoveEvent $event): void
    {
        $agreements = $this->contractTypeDictionaryRepository->getActiveAgreementsForContractType($event->getId());

        if(!empty($agreements)){
            throw new RemoveContractTypeOnActiveAgreementsException();
        }
    }
}
