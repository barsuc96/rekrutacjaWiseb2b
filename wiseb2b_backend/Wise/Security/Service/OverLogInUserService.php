<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use Exception;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\CommonLogicException\CannotPerformOperationException;
use Wise\Core\Service\OverLoginUserParams;
use Wise\Core\Service\SessionParamServiceInterface;
use Wise\Security\ApiUi\Model\UserLoginInfo;
use Wise\Security\Exception\OverLoginOnlyOneTimeException;
use Wise\Security\Service\Events\OverLogInUserServiceFinished;
use Wise\Security\Service\Interfaces\OverLogInUserServiceInterface;
use Wise\User\Domain\User\Exceptions\UserNotExistException;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Service\User\GetUserDetailsParams;
use Wise\User\Service\User\GetUserDetailsService;

/**
 * Serwis obsługuje przelogowanie na konto innego użytkownika
 */
class OverLogInUserService implements OverLogInUserServiceInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly SessionParamServiceInterface $sessionParamService,
        private readonly GetUserDetailsService $userDetailsService,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(OverLoginUserParams $overLoginUserParams): CommonServiceDTO
    {
        /** @var UserLoginInfo $loggedUser */
        $loggedUser = $this->security->getUser();

        $loggedUserData = $this->getUserDetails($loggedUser->getId());

        $wantToOverLogUserData = $this->getUserDetails($overLoginUserParams->getUserId());
        $wantToOverLogUserData = empty($wantToOverLogUserData) ? null : $wantToOverLogUserData;

        $existSessionParam = $this->sessionParamService->checkSessionParamExists(symbol: CurrentUserService::OVER_LOGGED_SYMBOL);

        $this->verifyCanOverLogin(
            currentUser: $loggedUserData,
            wantToOverlogUser: $wantToOverLogUserData,
            overLoginUserParams: $overLoginUserParams,
            existSessionParam: $existSessionParam
        );

        $this->sessionParamService->setSessionParam(
            symbol: CurrentUserService::OVER_LOGGED_SYMBOL,
            value: (string)$overLoginUserParams->getUserId()
        );

        $this->eventDispatcher->dispatch(new OverLogInUserServiceFinished(fromUserId: $loggedUserData['id'], toUserId: $overLoginUserParams->getUserId()), OverLogInUserServiceFinished::getName());

        return new CommonServiceDTO();
    }

    /**
     * Walidacja przed przelogowaniem
     * @param array $currentUser
     * @param array|null $wantToOverlogUser
     * @param OverLoginUserParams $overLoginUserParams
     * @param bool $existSessionParam
     * @return void
     */
    protected function verifyCanOverLogin(
        array $currentUser,
        ?array $wantToOverlogUser,
        OverLoginUserParams $overLoginUserParams,
        bool $existSessionParam = false
    ): void {

        // Użytkownik, na którego chce się przelogować musi istnieć
        if ($wantToOverlogUser === null) {
            throw UserNotExistException::id($overLoginUserParams->getUserId());
        }

        // Musi mieć uprawnienia superadmina, admina lub tradera
        if (!in_array(
            UserRoleEnum::tryFrom($currentUser['roleId']),
            [UserRoleEnum::ROLE_ADMIN, UserRoleEnum::ROLE_USER_MAIN, UserRoleEnum::ROLE_TRADER],
            true
        )) {
            throw new CannotPerformOperationException();
        }

        // Jeśli ma uprawnienia administratora to może tylko przelogować się na członków swojej firmy
        if(in_array(UserRoleEnum::tryFrom($currentUser['roleId']), [UserRoleEnum::ROLE_USER_MAIN], true) && $currentUser['clientId'] !== $wantToOverlogUser['clientId']){
            throw new CannotPerformOperationException();
        }

        // Musi wylogować się przed kolejnym przelogowaniem
        if ($existSessionParam) {
            throw new OverLoginOnlyOneTimeException();
        }
    }

    /**
     * Pobiera szczegóły użytkownika
     * @param int $userId
     * @return array|null
     */
    protected function getUserDetails(int $userId): ?array
    {
        $userDetailsParams = new GetUserDetailsParams();

        $userDetailsParams
            ->setUserId($userId)
            ->setFields([
                'id' => 'id',
                'roleId' => 'roleId',
                'clientId' => 'clientId',
            ]);

        try {
            return ($this->userDetailsService)($userDetailsParams)->read();
        } catch (Throwable) {
            return null;
        }
    }
}
