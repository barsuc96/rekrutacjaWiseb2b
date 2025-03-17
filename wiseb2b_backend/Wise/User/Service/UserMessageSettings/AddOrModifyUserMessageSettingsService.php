<?php

namespace Wise\User\Service\UserMessageSettings;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\User\Domain\UserMessageSettings\UserMessageSettings;
use Wise\User\Domain\UserMessageSettings\UserMessageSettingsRepositoryInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\AddOrModifyUserMessageSettingsServiceInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\AddUserMessageSettingsServiceInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\ModifyUserMessageSettingsServiceInterface;

class AddOrModifyUserMessageSettingsService implements AddOrModifyUserMessageSettingsServiceInterface
{
    public function __construct(
        protected readonly UserMessageSettingsRepositoryInterface $repository,
        protected readonly ModifyUserMessageSettingsServiceInterface $modifyService,
        protected readonly AddUserMessageSettingsServiceInterface $addService
    ) {}

    public function __invoke(CommonModifyParams $params): CommonServiceDTO
    {
        $userMessageExists = false;

        // Pobranie danych z DTO
        $data = $params->read();

        $userMessageExists = $this->findUserMessage($data);

        // Podane ustawienie istnieje, następuje aktualizacja danych
        if ($userMessageExists) {
            return ($this->modifyService)($params);
        }

        // Podana ustawienie nie istnieje, następuje utworzenie nowej
        return ($this->addService)($params);
    }

    private function findUserMessage(?array $data): bool
    {
        $userMessageExists = false;

        // Sprawdzenie, czy podane ustawienie istnieje
        $id = $data['id'] ?? null;
        if ($id) {
            $userMessageExists = $this->repository->isExists(['id' => $id]);
        }

        $userId = $data['userId'] ?? null;
        $messageSettingsId = $data['messageSettingsId'] ?? null;
        if (!$userMessageExists && $userId && $messageSettingsId) {
            $userMessageExists = $this->repository->isExists(['userId' => $userId, 'messageSettingsId' => $messageSettingsId]);
        }

        $clientId = $data['clientId'] ?? null;
        $messageSettingsId = $data['messageSettingsId'] ?? null;
        if (!$userMessageExists && $clientId && $messageSettingsId) {
            $userMessageExists = $this->repository->isExists(['clientId' => $clientId, 'messageSettingsId' => $messageSettingsId]);
        }

        return $userMessageExists;
    }
}
