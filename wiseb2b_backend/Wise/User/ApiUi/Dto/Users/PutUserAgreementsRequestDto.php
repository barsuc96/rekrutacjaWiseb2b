<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PutUserAgreementsRequestDto extends AbstractDto
{
    protected int $userId;

    #[OA\Property(
        description: 'Identyfikator zgody',
        example: 2,
    )]
    protected int $agreementId;
    #[OA\Property(
        description: 'Czy użytkownik akceptuję zgodę czy odrzuca',
        example: true,
    )]
    protected bool $granted;

    public function getAgreementId(): int
    {
        return $this->agreementId;
    }

    public function setAgreementId(int $agreementId): self
    {
        $this->agreementId = $agreementId;

        return $this;
    }

    public function isGranted(): bool
    {
        return $this->granted;
    }

    public function setGranted(bool $granted): self
    {
        $this->granted = $granted;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userid): self
    {
        $this->userId = $userid;

        return $this;
    }
}
