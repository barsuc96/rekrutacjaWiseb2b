<?php

declare(strict_types=1);

namespace Wise\User\Service\User\DataProvider;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use \Exception;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Domain\User\UserRoleEnum;

#[AutoconfigureTag(name: 'details_provider.user')]
class UserLoggedUserProvider extends AbstractAdditionalFieldProvider implements UserDetailsProviderInterface
{
    public const FIELD = 'loggedUser';

    public function __construct(
        private readonly Security $security,
        private readonly UserRepositoryInterface $userRepository,
        private readonly TranslatorInterface $translator
    ) {}

    /**
     * Sprawdzamy, czy pod użytkownika zalogowanego ktoś się podszywa, czyli ktoś się przelogował na tego użytkownika
     *
     * @throws Exception
     */
    public function getFieldValue($userId, ?array $cacheData = null): mixed
    {
        $loggedUser = $this->security->getUser();

        $user = $this->userRepository->findOneBy(['login' => $loggedUser->getUserIdentifier()]);

        return [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'role' => $this->translator->trans('user.role.' . UserRoleEnum::getRoleName($user->getRoleId())[0])
        ];
    }
}
