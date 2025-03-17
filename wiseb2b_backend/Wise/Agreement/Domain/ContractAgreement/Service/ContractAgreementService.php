<?php

namespace Wise\Agreement\Domain\ContractAgreement\Service;

use Wise\Agreement\Domain\Contract\Contract;
use Wise\Agreement\Domain\ContractAgreement\ContractAgreementRepositoryInterface;
use Wise\Agreement\Domain\ContractAgreement\Exception\ContractAgreementNotFoundException;
use Wise\Agreement\Domain\ContractAgreement\Service\Interfaces\ContractAgreementServiceInterface;
use Wise\Core\Domain\AbstractEntityDomainService;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;
use Wise\Core\Helper\QueryFilter\QueryJoinsHelper;
use Wise\Core\Model\QueryJoin;
use Wise\User\Domain\User\User;

class ContractAgreementService extends AbstractEntityDomainService implements ContractAgreementServiceInterface
{
    public function __construct(
        private readonly ContractAgreementRepositoryInterface $repository,
        private readonly ?EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper
    ){
        parent::__construct(
            repository:  $repository,
            notFoundException: ContractAgreementNotFoundException::class,
            entityDomainServiceShareMethodsHelper: $entityDomainServiceShareMethodsHelper
        );
    }

    public function prepareJoins(?array $fieldsArray): array
    {
        $fieldsWhichRequireJoin = QueryJoinsHelper::prepareFieldsWhichRequireJoinsByFieldNames($fieldsArray);

        $joins = [];
        if (array_key_exists('userId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(User::class, 'userId', ['userId' => 'userId.id']);
        }

        if (array_key_exists('contractId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(Contract::class, 'contractId', ['contractId' => 'contractId.id']);
        }

        return $joins;
    }
}
