<?php

namespace Wise\User\Service\UserMessageSettings;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\Core\Exception\ObjectExistsException;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\User\Domain\UserMessageSettings\MessageSettingsRepositoryInterface;
use Wise\User\Domain\UserMessageSettings\UserMessageSettingsFactory;
use Wise\User\Domain\UserMessageSettings\UserMessageSettingsRepositoryInterface;
use Wise\User\Service\User\Interfaces\UserHelperInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\AddUserMessageSettingsServiceInterface;

class AddUserMessageSettingsService implements AddUserMessageSettingsServiceInterface
{

    public function __construct(
        protected readonly UserMessageSettingsRepositoryInterface $repository,
        protected readonly MessageSettingsRepositoryInterface $messageSettingsRepository,
        protected readonly UserMessageSettingsFactory $userMessageSettingsFactory,
        protected readonly DomainEventsDispatcher $eventsDispatcher,
        protected readonly UserHelperInterface $userHelper
    ) {}

    public function __invoke(CommonServiceDTO $userMessageServiceDto): CommonServiceDTO
    {
        $newUserMessageData = $userMessageServiceDto->read();
        $this->validateParams($newUserMessageData);
        $this->findUserMessage($newUserMessageData);

        $newUserMessageData = $this->userMessageSettingsFactory->create($userMessageServiceDto);
        $newUserMessageData->validate();

        $this->eventsDispatcher->flushInternalEvents();
        $this->repository->save($newUserMessageData, true);
        $this->userMessageSettingsFactory->entityHasCreated($newUserMessageData);
        $this->eventsDispatcher->flush();

        ($resultDTO = new CommonServiceDTO())->write($newUserMessageData);
        return $resultDTO;
    }

    protected function validateParams(array $data): void
    {
        $messageSettingsId = $data['messageSettingsId'] ?? null;

        if($messageSettingsId === null){
            throw new InvalidInputArgumentException("Nie podano id zgody.");
        }

        if(!$this->messageSettingsRepository->isExists(['id' => $messageSettingsId])){
            throw new ValidationException("Podane ustawienie komunikatu o id ustawienia {$messageSettingsId} nie istnieje.");
        }
    }

    private function findUserMessage(?array $data): void
    {
        $userMessage = null;

        // Pobranie ustawienia komunikatu dla użytkownika
        $id = $data['id'] ?? null;
        if ($id) {
            $userMessage = $this->repository->findOneBy(['id' => $id]);
        }

        // Pobranie ustawienia komunikatu dla użytkownika
        $userId = $data['userId'] ?? null;
        $messageSettingsId = $data['messageSettingsId'] ?? null;
        if ($userMessage == null && $userId !== null && $messageSettingsId !== null) {
            $userMessage = $this->repository->findOneBy([
                'userId' => $userId,
                'messageSettingsId' => $messageSettingsId
            ]);
        }

        // Pobranie ustawienia komunikatu dla użytkownika
        $clientId = $data['clientId'] ?? null;
        $messageSettingsId = $data['messageSettingsId'] ?? null;
        if ($userMessage == null && $clientId !== null && $messageSettingsId !== null) {
            $userMessage = $this->repository->findOneBy([
                'clientId' => $clientId,
                'messageSettingsId' => $messageSettingsId
            ]);
        }

        if($userMessage){
            throw new ObjectExistsException("Podane ustawienie komunikatu o ID {$id} już istnieje.");
        }
    }
}
