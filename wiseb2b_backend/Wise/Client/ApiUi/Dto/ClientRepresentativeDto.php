<?php

namespace Wise\Client\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractResponseDto;

class ClientRepresentativeDto extends AbstractResponseDto
{

    #[OA\Property(
        description: 'Imię klienta',
        example: 'Jan',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Imię, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected ?string $personFirstname;

    #[OA\Property(
        description: 'Nazwisko klienta',
        example: 'Kowalski',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Nazwisko, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected ?string $personLastname;

    public function getPersonFirstname(): ?string
    {
        return $this->personFirstname;
    }

    public function setPersonFirstname(?string $personFirstname): self
    {
        $this->personFirstname = $personFirstname;

        return $this;
    }

    public function getPersonLastname(): ?string
    {
        return $this->personLastname;
    }

    public function setPersonLastname(?string $personLastname): self
    {
        $this->personLastname = $personLastname;

        return $this;
    }
}