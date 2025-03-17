<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\UserAgreements;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class DeleteUserAgreementsByKeyAttributesDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id zewnętrzne UserAgreements, nadane w ERP',
        example: 'XYZ-ASD-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Id zewnętrzne UserAgreements, może mieć maksymalnie 255 znaków",
    )]
    protected string $userAgreementId;

    public function getUserAgreementId(): string
    {
        return $this->userAgreementId;
    }

    public function setUserAgreementId(string $userAgreementId): self
    {
        $this->userAgreementId = $userAgreementId;

        return $this;
    }
}
