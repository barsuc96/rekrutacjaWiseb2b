<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Dto\Admin;

use Wise\Core\Dto\AbstractResponseDto;

class ReplicationObjectHistoryDto extends AbstractResponseDto
{
    protected ?int $id;
    protected ?int $idRequest;
    protected ?string $object;
    protected ?string $responseMessage;
    protected ?int $responseStatus;
    protected ?string $dateUpdate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getIdRequest(): ?int
    {
        return $this->idRequest;
    }

    public function setIdRequest(?int $idRequest): self
    {
        $this->idRequest = $idRequest;

        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(?string $object): self
    {
        $this->object = $object;

        return $this;
    }

    public function getResponseMessage(): ?string
    {
        return $this->responseMessage;
    }

    public function setResponseMessage(?string $responseMessage): self
    {
        $this->responseMessage = $responseMessage;

        return $this;
    }

    public function getResponseStatus(): ?int
    {
        return $this->responseStatus;
    }

    public function setResponseStatus(?int $responseStatus): self
    {
        $this->responseStatus = $responseStatus;

        return $this;
    }

    public function getDateUpdate(): ?string
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?string $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }
}
