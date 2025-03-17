<?php

namespace Wise\Agreement\Domain\Contract\Service;

use Wise\Agreement\Domain\Contract\Contract;
use Wise\Agreement\Domain\Contract\ContractRepositoryInterface;
use Wise\Agreement\Domain\Contract\Exception\ContractNotFoundException;
use Wise\Agreement\Domain\Contract\Service\Interfaces\ContractServiceInterface;
use Wise\Core\Domain\AbstractEntityDomainService;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Helper\QueryFilter\QueryJoinsHelper;
use Wise\Core\Model\QueryJoin;
use Wise\User\Domain\User\User;

class ContractService extends AbstractEntityDomainService implements ContractServiceInterface
{
    public function __construct(
        private readonly ContractRepositoryInterface $repository,
        private readonly ?EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper
    ){
        parent::__construct(
            repository:  $repository,
            notFoundException: ContractNotFoundException::class,
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

        return $joins;
    }
}
