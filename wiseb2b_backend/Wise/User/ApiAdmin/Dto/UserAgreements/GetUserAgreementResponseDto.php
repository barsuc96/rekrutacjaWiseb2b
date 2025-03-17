<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\UserAgreements;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\DateInsertUpdateDtoTrait;
use Wise\Core\Dto\AbstractResponseDto;
use DateTimeInterface;

class GetUserAgreementResponseDto extends AbstractResponseDto
{
    use DateInsertUpdateDtoTrait;

    #[OA\Property(
        description: 'Id zewnętrzne',
        example: 'USERAGREEMENT-123',
    )]
    protected string $id;

    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'ID zewnętrzne użytkownika',
        example: 'USER-123',
    )]
    protected string $userId;

    #[OA\Property(
        description: 'ID wewnętrzne użytkownika',
        example: 1,
    )]
    protected ?int $userInternalId;

    #[OA\Property(
        description: 'ID zewnętrzne zgody',
        example: 'AGREEMENT-123',
    )]
    protected string $agreementId;

    #[OA\Property(
        description: 'ID wewnętrzne zgody',
        example: 1,
    )]
    protected ?int $agreementInternalId;

    #[OA\Property(
        description: 'Data wyrażenia zgody',
        example: '2023-02-01 00:00:00',
    )]
    protected DateTimeInterface $agreeDate;

    #[OA\Property(
        description: 'IP z którego wyrażono zgodę',
        example: '192.168.0.1',
    )]
    protected string $agreeIp;

    #[OA\Property(
        description: 'Data niewyrażenia zgody',
        example: '2023-02-01 00:00:00',
    )]
    protected DateTimeInterface $disagreeDate;

    #[OA\Property(
        description: 'IP z którego zrezygnowano ze zgody',
        example: '192.168.0.1',
    )]
    protected string $disagreeIp;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInternalId(): int
    {
        return $this->internalId;
    }

    public function setInternalId(int $internalId): self
    {
        $this->internalId = $internalId;

        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserInternalId(): ?int
    {
        return $this->userInternalId;
    }

    public function setUserInternalId(?int $userInternalId): self
    {
        $this->userInternalId = $userInternalId;

        return $this;
    }

    public function getAgreementId(): string
    {
        return $this->agreementId;
    }

    public function setAgreementId(string $agreementId): self
    {
        $this->agreementId = $agreementId;

        return $this;
    }

    public function getAgreementInternalId(): ?int
    {
        return $this->agreementInternalId;
    }

    public function setAgreementInternalId(?int $agreementInternalId): self
    {
        $this->agreementInternalId = $agreementInternalId;

        return $this;
    }

    public function getAgreeDate(): DateTimeInterface
    {
        return $this->agreeDate;
    }

    public function setAgreeDate(DateTimeInterface $agreeDate): self
    {
        $this->agreeDate = $agreeDate;

        return $this;
    }

    public function getAgreeIp(): string
    {
        return $this->agreeIp;
    }

    public function setAgreeIp(string $agreeIp): self
    {
        $this->agreeIp = $agreeIp;

        return $this;
    }

    public function getDisagreeDate(): DateTimeInterface
    {
        return $this->disagreeDate;
    }

    public function setDisagreeDate(DateTimeInterface $disagreeDate): self
    {
        $this->disagreeDate = $disagreeDate;

        return $this;
    }

    public function getDisagreeIp(): string
    {
        return $this->disagreeIp;
    }

    public function setDisagreeIp(string $disagreeIp): self
    {
        $this->disagreeIp = $disagreeIp;

        return $this;
    }
}
