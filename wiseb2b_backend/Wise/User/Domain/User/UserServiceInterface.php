<?php

declare(strict_types=1);

namespace Wise\User\Domain\User;

use Doctrine\ORM\EntityNotFoundException;
use Wise\Core\Domain\Interfaces\EntityDomainServiceInterface;

interface UserServiceInterface extends EntityDomainServiceInterface
{
    public function getRoleSymbol(int $roleId): string;
    public function prepareJoins(?array $fieldsArray): array;
    public function getName($firstName, $lastName): ?string;

    /**
     * Weryfikuje czy przekazana rola jest wyższa od ról zalogowanego użytkownika
     * @param array $targetUserRoles Lista ról użytkownika do weryfikacji
     * @return bool
     * @throws EntityNotFoundException
     */
    public function isUserRolesHigher(array $targetUserRoles): bool;
}
