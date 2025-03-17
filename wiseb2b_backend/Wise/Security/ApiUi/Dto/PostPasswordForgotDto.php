<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PostPasswordForgotDto extends AbstractDto
{
    #[OA\Property(
        description: 'Adres email użytkownika który chce zresetować hasło',
        example: 'biuro@sente.pl',
    )]
    protected string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): PostPasswordForgotDto
    {
        $this->email = $email;

        return $this;
    }
}
