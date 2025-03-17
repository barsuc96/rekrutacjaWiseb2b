<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\UserAgreements;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;

class GetUserAgreementsQueryParametersDto extends CommonGetAdminApiDto
{
    #[OA\Property(
        description: 'Id zewnętrzne',
        example: 'USERAGREEMENT-123',
    )]
    protected string $id;

    #[OA\Property(
        description: 'ID zewnętrzne użytkownika',
        example: 'USER-123',
    )]
    protected string $userId;

    #[OA\Property(
        description: 'ID zewnętrzne zgody',
        example: 'AGREEMENT-123',
    )]
    protected string $agreementId;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

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

    public function getAgreementId(): string
    {
        return $this->agreementId;
    }

    public function setAgreementId(string $agreementId): self
    {
        $this->agreementId = $agreementId;

        return $this;
    }
}
