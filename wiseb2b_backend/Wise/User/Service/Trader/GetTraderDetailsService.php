<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader;

use Exception;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\User\Domain\Trader\TraderRepositoryInterface;
use Wise\User\Service\Trader\Interfaces\GetTraderDetailsServiceInterface;
use Wise\User\Service\User\UserAdditionalFieldsService;

/**
 * Serwis zwracający dane tradera
 */
class GetTraderDetailsService implements GetTraderDetailsServiceInterface
{
    public function __construct(
        private readonly TraderRepositoryInterface $repository
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(GetTraderDetailsParams $params): CommonServiceDTO
    {
        $filters = [
            new QueryFilter('id', $params->getTraderId())
        ];

        /**
         * Metoda getByIdView zwraca array z danymi tradera
         */
        $traderData = $this->repository->getByIdView(
            filters: $filters
        );

        ($resultDTO = new CommonServiceDTO())->writeAssociativeArray($traderData);

        return $resultDTO;
    }
}
