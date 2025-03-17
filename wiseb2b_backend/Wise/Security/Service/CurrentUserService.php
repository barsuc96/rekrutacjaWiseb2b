<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Security;
use Wise\Core\Domain\SessionParam;
use Wise\Core\Service\SessionParamServiceInterface;
use Wise\Security\ApiUi\Model\UserLoginInfo;
use Wise\Security\ApiUi\Service\UserLoginTransformerInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\User\Exceptions\UserNotExistException;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Domain\User\UserRoleEnum;

/**
 * Serwis zwraca obiekt obecnie zalogowanego użytkownika
 */
class CurrentUserService implements CurrentUserServiceInterface
{
    public const OVER_LOGGED_SYMBOL = 'over_logged';

    /**
     * Obiekt obecnie zalogowanego użytkownika
     * @var null
     */
    protected $userLoginInfo = null;

    /**
     * Czy użytkownik został ustawiony w obecnej sesji
     * Wykorzystywane w komendach, aby był tam zalogowany użytkownik
     * @var bool
     */
    protected bool $switchedUserInSession = false;

    /**
     * Id użytkownika, który został ustawiony w obecnej sesji
     * @var int|null
     */
    private ?int $selectedUserId = null;

    public function __construct(
        private readonly Security $security,
        private readonly SessionParamServiceInterface $sessionParamService,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserLoginTransformerInterface $userLoginTransformer,
    ) {
    }

    /**
     * Zwraca obiekt obecnie zalogowanego użytkownika,
     * w przypadku wystąpienia przelogowania na innego użytkownika
     * zwraca obiekt przelogowanego użytkownika
     * @return UserLoginInfo
     * @throws EntityNotFoundException
     */
    public function getCurrentUser(): UserLoginInfo
    {
        // Jeśli użytkownik został ustawiony w obecnej sesji, zwracamy go (wykorzystywane w komendach, aby był tam zalogowany użytkownik)
        if($this->userLoginInfo !== null && $this->switchedUserInSession){
            return $this->userLoginInfo;
        }

        // Jeśli ustawiono użytkownika to go zwróć
        if($this->selectedUserId !== null){
            return $this->getSelectedUser();
        }

        /** @var UserLoginInfo $loggedUser */
        $loggedUser = $this->security->getUser();

        // Obsługa przelogowania na innego użytkownika
        $sessionParam = $this->sessionParamService->getActiveSessionParam(self::OVER_LOGGED_SYMBOL);
        if ($sessionParam instanceof SessionParam) {
            return $this->getSessionUser($sessionParam, $loggedUser);
        }

        $this->userLoginInfo = $loggedUser;

        return $this->userLoginInfo;
    }

    /**
     * Zwraca id obecnie zalogowanego użytkownika
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getCurrentUser()->getId();
    }

    /**
     * Zwraca id klienta obecnie zalogowanego użytkownika,
     * w przypadku podania $userId jako argument, zwraca klienta podanego użytkownika
     * @param int|null $userId - id użytkownika, dla którego chcemy pobrać id klienta
     * @throws EntityNotFoundException
     */
    public function getClientId(?int $userId = null): int
    {
        if ($userId === null) {
            return $this->getCurrentUser()->getClientId();
        }

        $user = $this->userRepository->findOneBy(['id' => $userId]);
        if ($user === null) {
            throw new EntityNotFoundException("Nie znaleziono użytkownika od id: $userId");
        }

        return $user->getClientId();
    }

    /**
     * Zwraca role obecnie zalogowanego użytkownika,
     * w przypadku podania $userId jako argument, zwraca role podanego użytkownika
     * @param int|null $userId - id użytkownika, dla którego chcemy pobrać role
     * @throws EntityNotFoundException
     */
    public function getRoles(?int $userId = null): array
    {
        if ($userId === null) {
            return $this->getCurrentUser()->getRoles();
        }

        $user = $this->userRepository->findOneBy(['id' => $userId]);
        if ($user === null) {
            throw new EntityNotFoundException("Nie znaleziono użytkownika od id: $userId");
        }

        return $user->getRoles() ?? [];
    }

    /**
     * Sprawdza, czy użytkownik jest niezalogowany
     * @return bool
     * @throws EntityNotFoundException
     */
    public function isUnloggedUser()
    {
        if($this->security->getUser() === null){
            return true;
        }

        return in_array(UserRoleEnum::ROLE_OPEN_PROFILE->value, $this->getRoles());
    }

    /** Pozwala ustawić użytkownika (przydatne przy komendach) */
    public function setCurrentUser(?int $userId, ?int $clientId = null): void
    {
        if($userId == null){
            return;
        }

        if($clientId !== null){
            $this->setRandomUserFromClient($clientId);
        }

        $this->selectedUserId = $userId;
        $this->switchedUserInSession = true;
    }

    /**
     * Ustawia losowego użytkownika dla handlera lub komendy
     * @return void
     */
    public function setRandomUserForHandlerOrCommand(): void
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['roleId' => UserRoleEnum::ROLE_ADMIN->value]);

        $this->setCurrentUser($user->getId());
    }

    /**
     * Ustawia losowego użytkownika danego klienta
     * @param int $clientId
     * @return void
     */
    public function setRandomUserFromClient(int $clientId): void
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['clientId' => $clientId, 'isActive' => true]);

        $this->setCurrentUser($user->getId());
    }

    /**
     * Zwraca obiekt użytkownika, którego id zostało ustawione przez metodę setCurrentUser
     */
    protected function getSelectedUser(): UserLoginInfo
    {
        $user = $this->userRepository->findOneBy(['id' => $this->selectedUserId]);
        if ($user === null) {
            throw new EntityNotFoundException("Nie znaleziono użytkownika od id: $this->selectedUserId");
        }

        $userLoginInfo = $this->userLoginTransformer->transform(
            $user,
            'overlogged',
            true
        );

        $this->userLoginInfo = $userLoginInfo;

        return $this->userLoginInfo;
    }

    /**
     * Pobieramy użytkownika na podstawie parametrów sesji
     * W tym miejscu zwracamy obiekt przelogowanego użytkownika (ponieważ informacje o nim przechowujemy w zmiennych sesyjnych)
     * @param SessionParam $sessionParam
     * @param UserLoginInfo $loggedUser
     * @return UserLoginInfo
     */
    protected function getSessionUser(SessionParam $sessionParam, UserLoginInfo $loggedUser): UserLoginInfo
    {
        $user = $this->userRepository->find($sessionParam->getValue());
        if ($user === null) {
            throw UserNotExistException::id((int) $sessionParam->getValue());
        }

        $userLoginInfo = $this->userLoginTransformer->transform(
            $this->userRepository->find($sessionParam->getValue()),
            $loggedUser->getCurrentSessionId()
        );

        $userLoginInfo->setOverlogged(true);

        $this->userLoginInfo = $userLoginInfo;

        return $this->userLoginInfo;
    }
}
