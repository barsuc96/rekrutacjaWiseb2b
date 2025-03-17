<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Agreements;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class DeleteAgreementsByKeyAttributesDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id zewnętrzne Agreements, nadane w ERP',
        example: 'XYZ-ASD-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Id zewnętrzne Agreements, może mieć maksymalnie 255 znaków",
    )]
    protected string $agreementId;

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
