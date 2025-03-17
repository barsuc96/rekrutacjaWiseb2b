<?php

namespace Wise\Client\Domain\ClientRepresentative;

use Wise\Core\Model\AbstractModel;

class ClientRepresentative extends AbstractModel
{
    protected ?string $personFirstname;

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