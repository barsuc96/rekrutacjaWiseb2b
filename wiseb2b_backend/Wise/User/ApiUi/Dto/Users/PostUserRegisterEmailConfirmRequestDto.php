<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PostUserRegisterEmailConfirmRequestDto extends AbstractDto
{
    #[OA\Property(
        description: 'Poprzednie hasło',
        example: 'KEPlyVD1RZNi2U7F2KVTAsbNgt',
    )]
    protected string $hash;

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }


}
