<?php

declare(strict_types=1);

namespace Wise\User\Service\User\DataProvider;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\User\Exceptions\UserNotExistException;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Domain\User\UserRoleEnum;

/**
 * Zwraca przetłumaczoną role użytkownika
 */
#[AutoconfigureTag(name: 'details_provider.user')]
class UserRoleFormattedProvider extends AbstractAdditionalFieldProvider implements UserDetailsProviderInterface
{
    public const FIELD = 'roleFormatted';

    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly TranslatorInterface $translator
    ){}


    public function getFieldValue($userId, ?array $cacheData = null): mixed
    {
        $user = $this->repository->find($userId);
        if (!$user) {
            throw UserNotExistException::id($userId);
        }

        return $this->translator->trans('user.role.' . current(UserRoleEnum::getRoleName($user->getRoleId())));
    }
}
