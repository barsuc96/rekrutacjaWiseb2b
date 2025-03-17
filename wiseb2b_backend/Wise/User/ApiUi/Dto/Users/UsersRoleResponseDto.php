<?php

namespace Wise\User\ApiUi\Dto\Users;

use Wise\Core\Dto\AbstractResponseDto;
use OpenApi\Attributes as OA;

class UsersRoleResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator roli',
        example: 'ROLE_USER',
    )]
    private string $role;

    #[OA\Property(
        description: 'Nazwa roli do wyświetlenia',
        example: 'Użytkownik',
    )]
    private string $roleName;

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoleName(): string
    {
        return $this->roleName;
    }

    public function setRoleName(string $roleName): self
    {
        $this->roleName = $roleName;

        return $this;
    }


}