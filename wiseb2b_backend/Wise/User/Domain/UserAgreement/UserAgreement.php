<?php

declare(strict_types=1);

namespace Wise\User\Domain\UserAgreement;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\User\Repository\Doctrine\UserAgreementRepository;

#[ORM\Entity(repositoryClass: UserAgreementRepository::class)]
class UserAgreement extends AbstractEntity
{
    // Id zewnętrzne powiązania
    #[ORM\Column(nullable: true)]
    protected ?string $idExternal = null;

    // Id użytkownika
    #[ORM\Column(nullable: true)]
    protected ?int $userId = null;

    // Id klienta
    #[ORM\Column(nullable: true)]
    protected ?int $clientId = null;

    // ID definicji zgody
    #[ORM\Column(nullable: true)]
    protected ?int $agreementId = null;

    // IP z którego wyrażono zgodę
    #[ORM\Column(length: 60, nullable: true)]
    protected ?string $agreeIp = null;

    // Data akceptacji zgody, możemy zapisać tylko gdy data rezygnacji będzie nullem
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?\DateTimeInterface $agreeDate = null;

    // IP z którego zrezygnowano ze zgody
    #[ORM\Column(length: 60, nullable: true)]
    protected ?string $disagreeIp = null;

    // Data rezygnacji ze zgody,  możemy zapisać tylko gdy data akceptacji będzie nullem
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?\DateTimeInterface $disagreeDate = null;

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getAgreementId(): ?int
    {
        return $this->agreementId;
    }

    public function setAgreementId(?int $agreementId): self
    {
        $this->agreementId = $agreementId;

        return $this;
    }

    public function getAgreeIp(): ?string
    {
        return $this->agreeIp;
    }

    public function setAgreeIp(?string $agreeIp): self
    {
        $this->agreeIp = $agreeIp;

        return $this;
    }

    public function getAgreeDate(): ?\DateTimeInterface
    {
        return $this->agreeDate;
    }

    public function setAgreeDate(?\DateTimeInterface $agreeDate): self
    {
        $this->agreeDate = $agreeDate;

        return $this;
    }

    public function getDisagreeIp(): ?string
    {
        return $this->disagreeIp;
    }

    public function setDisagreeIp(?string $disagreeIp): self
    {
        $this->disagreeIp = $disagreeIp;

        return $this;
    }

    public function getDisagreeDate(): ?\DateTimeInterface
    {
        return $this->disagreeDate;
    }

    public function setDisagreeDate(?\DateTimeInterface $disagreeDate): self
    {
        $this->disagreeDate = $disagreeDate;

        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }
}
