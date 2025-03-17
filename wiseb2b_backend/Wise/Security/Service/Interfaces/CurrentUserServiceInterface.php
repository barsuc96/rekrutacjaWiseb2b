<?php

namespace Wise\Security\Service\Interfaces;

use Wise\Security\ApiUi\Model\UserLoginInfo;

interface CurrentUserServiceInterface
{
    public function getCurrentUser(): UserLoginInfo;
    public function getUserId(): int;
    public function getClientId(?int $userId = null): int;
    public function getRoles(?int $userId = null): array;
    public function isUnloggedUser();
    public function setCurrentUser(?int $userId, ?int $clientId = null): void;
    public function setRandomUserForHandlerOrCommand(): void;
}
