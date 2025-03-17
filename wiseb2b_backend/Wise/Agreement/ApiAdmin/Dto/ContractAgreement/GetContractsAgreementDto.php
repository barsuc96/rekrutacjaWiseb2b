<?php

namespace Wise\Agreement\ApiAdmin\Dto\ContractAgreement;

use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\ApiAdmin\Dto\AbstractSingleObjectAdminApiRequestDto;
use DateTimeInterface;
use Wise\Core\ApiAdmin\Dto\CommonAdminApiDto;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiAdmin\Dto\CommonDateInsertUpdateDtoTrait;

class GetContractsAgreementDto extends AbstractSingleObjectAdminApiRequestDto
{
    use CommonDateInsertUpdateDtoTrait;

    #[OA\Property(
        description: 'Id identyfikujące zgodę w ERP',
        example: 'CONTRACT-AGREEMENT-123',
    )]
    #[FieldEntityMapping('idExternal')]
    protected ?string $id = null;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    #[FieldEntityMapping('id')]
    protected ?int $internalId = null;

    #[OA\Property(
        description: 'ID użytkownika nadawane przez system ERP',
        example: 'USER-123',
    )]
    #[FieldEntityMapping('userId.idExternal')]
    protected ?string $userId = null;

    #[OA\Property(
        description: 'Identyfikator wewnętrzny użytkownika',
        example: 1,
    )]
    #[FieldEntityMapping('userId')]
    protected ?int $userInternalId = null;



    #[OA\Property(
        description: 'ID umowy nadawane przez system ERP',
        example: 'USER-123',
    )]
    #[FieldEntityMapping('contractId.idExternal')]
    protected ?string $contractId = null;

    #[OA\Property(
        description: 'Identyfikator wewnętrzny umowy',
        example: 1,
    )]
    #[FieldEntityMapping('contractId')]
    protected ?int $contractInternalId = null;

    #[OA\Property(
        description: 'IP z którego wyrażono zgodę',
        example: '4.321.45.213',
    )]
    protected ?string $agreeIp = null;


    #[OA\Property(
        description: 'Data akceptacji zgody',
        example: '2023-01-01 00:00:01',
    )]
    protected ?DateTimeInterface $agreeDate = null;

    #[OA\Property(
        description: 'IP z którego zrezygnowano ze zgody',
        example: '4.321.45.213',
    )]
    protected ?string $disagreeIp = null;

    #[OA\Property(
        description: 'Data rezygnacji ze zgody',
        example: '2023-01-01 00:00:01',
    )]
    protected ?DateTimeInterface $disagreeDate = null;

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

    public function getUserInternalId(): int
    {
        return $this->userInternalId;
    }

    public function setUserInternalId(int $userInternalId): self
    {
        $this->userInternalId = $userInternalId;

        return $this;
    }

    public function getContractId(): string
    {
        return $this->contractId;
    }

    public function setContractId(string $contractId): self
    {
        $this->contractId = $contractId;

        return $this;
    }

    public function getContractInternalId(): int
    {
        return $this->contractInternalId;
    }

    public function setContractInternalId(int $contractInternalId): self
    {
        $this->contractInternalId = $contractInternalId;

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

    public function getAgreeDate(): DateTimeInterface
    {
        return $this->agreeDate;
    }

    public function setAgreeDate(DateTimeInterface $agreeDate): self
    {
        $this->agreeDate = $agreeDate;

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

    public function getDisagreeDate(): DateTimeInterface
    {
        return $this->disagreeDate;
    }

    public function setDisagreeDate(DateTimeInterface $disagreeDate): self
    {
        $this->disagreeDate = $disagreeDate;

        return $this;
    }
}
