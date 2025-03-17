<?php

declare(strict_types=1);

namespace Wise\User\Service\User\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Domain\User\UserServiceInterface;

#[AutoconfigureTag(name: 'details_provider.user')]
class UserNameProvider extends AbstractAdditionalFieldProvider implements UserDetailsProviderInterface
{
    public const FIELD = 'name';

    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly UserServiceInterface $service,
    ) {}

    /**
     * Pobieramy pole name dla wybranego użytkownika
     *
     * @throws Exception
     */
    public function getFieldValue($userId, ?array $cacheData = null): mixed
    {
        /** @var User $user */
        $user = $this->repository->find($userId);

        if ($user) {
            return $this->service->getName($user->getFirstName(), $user->getLastName());
        }

        return '';
    }
}
