<?php

namespace Wise\User\Service\UserMessageSettings;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\Merge\MergeService;
use Wise\User\Domain\UserMessageSettings\MessageSettingsRepositoryInterface;
use Wise\User\Domain\UserMessageSettings\UserMessageSettings;
use Wise\User\Domain\UserMessageSettings\UserMessageSettingsRepositoryInterface;
use Wise\User\Service\User\Interfaces\UserHelperInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\ModifyUserMessageSettingsServiceInterface;

class ModifyUserMessageSettingsService implements ModifyUserMessageSettingsServiceInterface
{


    public function __construct(
        protected readonly UserMessageSettingsRepositoryInterface $repository,
        protected readonly MessageSettingsRepositoryInterface $messageSettingsRepository,
        protected readonly MergeService $mergeService,
        protected readonly DomainEventsDispatcher $eventsDispatcher,
        protected readonly UserHelperInterface $userHelper
    ) {
    }

    public function __invoke(CommonModifyParams $params): CommonServiceDTO
    {
        $userMessage = null;

        // Pobranie danych z DTO
        $data = $params->read();

        $this->validateParams($data);

        $userMessage = $this->findUserMessage($data);

        // Weryfikacja czy istnieje ustawienie komunikatu dla użytkownika
        if (!isset($userMessage) || !($userMessage instanceof UserMessageSettings)) {
            throw new ObjectNotFoundException("Podane ustawienie komunikatu nie istnieje.");
        }

        $this->mergeService->merge($userMessage, $data, $params->getMergeNestedObjects());

        $userMessage->validate();

        $this->eventsDispatcher->flushInternalEvents();
        $userMessage = $this->repository->save($userMessage);
        $this->eventsDispatcher->flush();

        ($resultDTO = new CommonServiceDTO())->write($userMessage);
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

    private function findUserMessage(?array $data): ?UserMessageSettings
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

        return $userMessage;
    }

}
