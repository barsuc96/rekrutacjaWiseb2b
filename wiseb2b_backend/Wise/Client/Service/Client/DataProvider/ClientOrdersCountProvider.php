<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\DataProvider;

use Exception;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;

class ClientOrdersCountProvider extends AbstractAdditionalFieldProvider implements ClientDetailsProviderInterface
{
    public const FIELD = 'ordersCount';

    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
    ) {}
    /**
     * Pobieramy dane dla danego użytkownika, używamy do tego serwisu aplikacji ListByFiltersAndSearchKeywordOrderService
     * @throws Exception
     */
    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
        return $this->clientRepository->getAdditionalDataById($entityId)['orderCount'];
    }
}
