<?php

declare(strict_types=1);

namespace Wise\User\Service\User\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;

#[AutoconfigureTag(name: 'details_provider.user')]
class UserTotalOffersProvider extends AbstractAdditionalFieldProvider implements UserDetailsProviderInterface
{
    public const FIELD = 'totalOffers';

    public function __construct(
    ) {}

    /**
     * Pobieramy dane dla danego użytkownika,
     * używamy do tego serwisu aplikacji ListOfferService
     *
     * @throws Exception
     */
    public function getFieldValue($userId, ?array $cacheData = null): mixed
    {
        return 0;
    }
}
