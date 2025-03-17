<?php

declare(strict_types=1);

namespace Wise\User\Domain\Trader;

use Wise\Core\Domain\AbstractEntityDomainService;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;
use Wise\Core\Helper\QueryFilter\QueryJoinsHelper;
use Wise\Core\Model\QueryJoin;
use Wise\User\Domain\Trader\Exceptions\TraderNotFoundException;

class TraderService extends AbstractEntityDomainService implements TraderServiceInterface
{
    public function __construct(
        private readonly TraderRepositoryInterface $repository,
        private readonly EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper
    ){
        parent::__construct(
            repository:  $repository,
            notFoundException: TraderNotFoundException::class,
            entityDomainServiceShareMethodsHelper: $entityDomainServiceShareMethodsHelper
        );
    }

    public function getName($firstName, $lastName): string
    {
        return $firstName . ' ' . $lastName;
    }

    /**
     * Metoda na podstawie wskazanych do wyciągnięcia pól ($fieldNames) przygotowuje joiny do zapytania
     */
    public function prepareJoins(?array $fieldsArray): array
    {
        $fieldsWhichRequireJoin = QueryJoinsHelper::prepareFieldsWhichRequireJoinsByFieldNames($fieldsArray);

        $joins = [];
        if (array_key_exists('userId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(Trader::class, 'userId', ['userId' => 'userId.id']);
        }

        return $joins;
    }
}
