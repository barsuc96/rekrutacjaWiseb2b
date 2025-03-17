<?php

declare(strict_types=1);

namespace Wise\User\Service\User\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\UserLoginHistory\UserLoginHistoryRepositoryInterface;

#[AutoconfigureTag(name: 'details_provider.user')]
class UserChangePasswordProvider extends AbstractAdditionalFieldProvider implements UserDetailsProviderInterface
{
    public const FIELD = 'changePassword';

    public function __construct(
        private readonly UserLoginHistoryRepositoryInterface $repository,
    ) {}

    /**
     * Pobieramy dane histori logowania dla danego użytkownika, w celu określenia czy mamy wymagać zmiany hasła
     *
     * @throws Exception
     */
    public function getFieldValue($userId, ?array $cacheData = null): mixed
    {
        /**
         * Jeśli użytkownik zalogował się maksymalnie 1 raz, to wymagamy zmiane hasła
         *
         * TODO, spowodowany tym że nie działa zapisywania danych logowania się do systemu, to narazie wymuszam,
         * aby front zawsze dostawał info że nie trzeba zmieniać hasła
         */
        /*
        $userLoginHistoryCount = $this->repository->getTotalCountByQueryFilters([new QueryFilter('userId', $userId)]);
        return $userLoginHistoryCount <= 1;
         */
        return false;
    }
}
