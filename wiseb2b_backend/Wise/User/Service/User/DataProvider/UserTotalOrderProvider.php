<?php

declare(strict_types=1);

namespace Wise\User\Service\User\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;

#[AutoconfigureTag(name: 'details_provider.user')]
class UserTotalOrderProvider  extends AbstractAdditionalFieldProvider implements UserDetailsProviderInterface
{
    public const FIELD = 'totalOrders';

    public function __construct() {}

    /**
     * Pobieramy dane dla danego użytkownika, używamy do tego serwisu aplikacji ListByFiltersAndSearchKeywordOrderService
     *
     * @throws Exception
     */
    public function getFieldValue($userId, ?array $cacheData = null): mixed
    {
        return 0;
    }
}
