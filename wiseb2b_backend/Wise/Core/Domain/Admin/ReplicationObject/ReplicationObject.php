<?php

declare(strict_types=1);


namespace Wise\Core\Domain\Admin\ReplicationObject;

use DateTime;

/**
 * Obiekt reprezentujący wpis o replikacji danego obiektu z tablicy wysyłanej do AdminApi
 */
class ReplicationObject
{
    /**
     * @var int $id - identyfikator wpisu o replikacji danego obiektu
     */
    protected int $id;
    /**
     * @var int $idRequest - identyfikator requestu
     */
    protected int $idRequest;
    /**
     * @var string $object - replikowany obiekt w formacie json
     */
    protected string $object;
    /**
     * @var string $objectClass - klasa replikowanego obiektu
     */
    protected string $objectClass;
    /**
     * @var string $responseBody - ciało odpowiedzi admin api dla danego obiektu
     */
    protected string $responseMessage;
    /**
     * @var int $responseStatus - status odpowiedzi admin api dla danego obiektu zgodnie z enumem ResponseStatusEnum
     */
    protected int $responseStatus;
    /**
     * @var DateTime|null $sysInsertDate - data utworzenia wpisu o replikacji danego obiektu
     */
    protected ?DateTime $sysInsertDate;
    /**
     * @var DateTime|null $sysUpdateDate - data ostatniej aktualizacji wpisu o replikacji danego obiektu
     */
    protected ?DateTime $sysUpdateDate;

    // TODO JCZ: do usunięcia - domena zarządza tym co się w niej zmienia a nie repo
    public function preInsert(): void
    {
        $date = new DateTime();
        $this->sysInsertDate = $date;
        $this->sysUpdateDate = $date;
    }

    // TODO JCZ: do usunięcia - domena zarządza tym co się w niej zmienia a nie repo
    public function preUpdate(): void
    {
        $this->sysUpdateDate = new DateTime();
    }

    public function getObjectClass(): string
    {
        return $this->objectClass;
    }

    public function setObjectClass(string $objectClass): self
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getIdRequest(): int
    {
        return $this->idRequest;
    }

    public function setIdRequest(int $idRequest): self
    {
        $this->idRequest = $idRequest;

        return $this;
    }

    public function getObject(): string
    {
        return $this->object;
    }

    public function setObject(string $object): self
    {
        $this->object = $object;

        return $this;
    }

    public function getResponseMessage(): string
    {
        return $this->responseMessage;
    }

    public function setResponseMessage(string $responseMessage): self
    {
        $this->responseMessage = $responseMessage;

        return $this;
    }

    public function getResponseStatus(): int
    {
        return $this->responseStatus;
    }

    public function setResponseStatus(int $responseStatus): self
    {
        $this->responseStatus = $responseStatus;

        return $this;
    }

    public function getSysInsertDate(): ?DateTime
    {
        return $this->sysInsertDate;
    }

    public function setSysInsertDate(?DateTime $sysInsertDate): self
    {
        $this->sysInsertDate = $sysInsertDate;

        return $this;
    }

    public function getSysUpdateDate(): ?DateTime
    {
        return $this->sysUpdateDate;
    }

    public function setSysUpdateDate(?DateTime $sysUpdateDate): self
    {
        $this->sysUpdateDate = $sysUpdateDate;

        return $this;
    }
}
