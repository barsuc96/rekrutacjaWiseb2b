<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use DateTimeImmutable;
use Exception;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Exception\ObjectExistsException;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\User\Domain\User\Events\UserLoggedInEvent;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Domain\UserLoginHistory\UserLoginHistory;
use Wise\User\Domain\UserLoginHistory\UserLoginHistoryRepositoryInterface;
use Wise\User\Service\User\Interfaces\AddUserLoginHistoryServiceInterface;

/**
 * Dodaje nowy wpis do historii logowań użytkownika.
 */
class AddUserLoginHistoryService implements AddUserLoginHistoryServiceInterface
{
    public function __construct(
        private readonly UserLoginHistoryRepositoryInterface $repository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly DomainEventsDispatcher $eventsDispatcher,
    ) {
    }

    /**
     * @throws ObjectExistsException
     * @throws Exception
     */
    public function __invoke(CommonModifyParams $userLoginHistoryDto): CommonModifyParams
    {
        $newUserLoginHistoryData = $userLoginHistoryDto->read();
        $id = $newUserLoginHistoryData['id'] ?? null;
        $user = null;

        if ($this->repository->findOneBy(['id' => $id])) {
            throw new ObjectExistsException('Obiekt w bazie już istnieje');
        }

        if (isset($newUserLoginHistoryData['userId.login'])) {
            $user = $this->userRepository->findOneBy(['login' => $newUserLoginHistoryData['userId.login']]);

            if (is_null($user)) {
                throw new ObjectExistsException(
                    'Nie znaleziono użytkownika o podanym loginie: '.$newUserLoginHistoryData['userId.login']
                );
            }

            $newUserLoginHistoryData['userId'] = $user->getId();
            unset($newUserLoginHistoryData['userId.login']);
        }

        $newUserLoginHistory = (new UserLoginHistory());
        // Robimy merge do pustego obiektu, a nie create ponieważ create nie obsługuje nam typu collection
        $newUserLoginHistory->merge($newUserLoginHistoryData);

        $newUserLoginHistory->setLoginDate(new DateTimeImmutable());

        $newUserLoginHistory->validate();

        $newUserLoginHistory = $this->repository->save($newUserLoginHistory, true);

        // Wysyłamy event o zalogowanym użytkowniku
        $this->sendEventAboutLoggedInUser($user);

        ($resultDTO = new CommonModifyParams())->write($newUserLoginHistory);

        return $resultDTO;
    }

    /**
     * Wysyła event o zalogowanym użytkowniku.
     */
    protected function sendEventAboutLoggedInUser(?AbstractEntity $user)
    {
        if($user === null){
            return;
        }

        // Wysłanie eventu o zalogowaniu użytkownika
        DomainEventManager::instance()->post(new UserLoggedInEvent($user->getId()));
        $this->eventsDispatcher->flush();
    }
}
