<?php

namespace Wise\Agreement\Service\Contract\DataProvider;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Agreement\Domain\Contract\ContractRepositoryInterface;
use Wise\Agreement\Domain\Contract\Enum\ContractStatus;
use Wise\Agreement\Domain\Contract\Exception\ContractNotFoundException;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;

/**
 * Provider umożliwiający pobranie statusu umowy w formie sformatowanej
 */
#[AutoconfigureTag(name: 'details_provider.contract')]
class ContractStatusFormatted extends AbstractAdditionalFieldProvider implements ContractProviderInterface
{
    public const FIELD = 'statusFormatted';

    public function __construct(
        private readonly ContractRepositoryInterface $repository,
        private readonly TranslatorInterface $translator,
    ){}

    public function getFieldValue($contractId): mixed
    {
        $contract = $this->repository->find($contractId);
        if(!$contract){
            throw ContractNotFoundException::id($contractId);
        }

        $status = ContractStatus::from($contract->getStatus());

        return $this->translator->trans('ContractStatus.' . $status);
    }
}
