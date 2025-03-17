<?php

namespace Wise\User\Service\User\DataProvider;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Domain\User\UserRoleEnum;

#[AutoconfigureTag(name: 'details_provider.user')]
class UserRoleProvider extends AbstractAdditionalFieldProvider implements UserDetailsProviderInterface
{
    public const FIELD = 'role';

    public function __construct(
        private readonly UserRepositoryInterface $repository,
    ){}


    public function getFieldValue($userId, ?array $cacheData = null): mixed
    {
        $user = $this->repository->find($userId);
        if (!$user) {
            throw new ObjectNotFoundException('Nie udało się znaleźć użytkownika o id: ' . $userId);
        }

        return current(UserRoleEnum::getRoleName($user->getRoleId()));
    }
}
