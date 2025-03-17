<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\DataProvider;

use Exception;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;

class ClientOffersCountProvider extends AbstractAdditionalFieldProvider implements ClientDetailsProviderInterface
{
    public const FIELD = 'offersCount';

    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
    ) {
    }

    /**
     * Pobieramy dane dla danego użytkownika,
     * używamy do tego serwisu aplikacji ListByFiltersAndSearchKeywordClientService
     *
     * @throws Exception
     */
    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
        return $this->clientRepository->getAdditionalDataById($entityId)['offerCount'];
    }
}
