<?php

namespace Wise\GPSR\Domain\GpsrSupplier\Service;

use Wise\Core\Domain\AbstractEntityDomainService;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;
use Wise\Core\Helper\QueryFilter\QueryJoinsHelper;
use Wise\GPSR\Domain\GpsrSupplier\Exception\GpsrSupplierNotFoundException;
use Wise\GPSR\Domain\GpsrSupplier\Service\Interfaces\SupplierServiceInterface;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplierRepositoryInterface;

class SupplierService extends AbstractEntityDomainService implements SupplierServiceInterface
{
    public function __construct(
        private readonly GpsrSupplierRepositoryInterface $repository,
        private readonly ?EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper
    ){
        parent::__construct(
            repository:  $repository,
            notFoundException: GpsrSupplierNotFoundException::class,
            entityDomainServiceShareMethodsHelper: $entityDomainServiceShareMethodsHelper
        );
    }

    public function prepareJoins(?array $fieldsArray): array
    {
        $fieldsWhichRequireJoin = QueryJoinsHelper::prepareFieldsWhichRequireJoinsByFieldNames($fieldsArray);

        $joins = [];
//        if (array_key_exists('userId', $fieldsWhichRequireJoin)) {
//            $joins[] = new QueryJoin(User::class, 'userId', ['userId' => 'userId.id']);
//        }

        return $joins;
    }
}
