<?php

declare(strict_types=1);

namespace Wise\User\Service\User\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;

#[AutoconfigureTag(name: 'details_provider.user')]
class UserOverloggedProvider extends AbstractAdditionalFieldProvider implements UserDetailsProviderInterface
{
    public const FIELD = 'overlogged';

    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
    ) {}

    /**
     * Sprawdzamy, czy pod użytkownika zalogowanego ktoś się podszywa, czyli ktoś się przelogował na tego użytkownika
     *
     * @throws Exception
     */
    public function getFieldValue($userId, ?array $cacheData = null): mixed
    {
        return $this->currentUserService->getCurrentUser()->isOverlogged();
    }
}
