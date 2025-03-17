<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Dto\Countries;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class DeleteCountriesByKeyAttributesDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id zewnętrzne Countries, nadane w ERP',
        example: 'XYZ-ASD-123',
    )]
    #[Assert\Length(
        max: 3,
        maxMessage: "Id zewnętrzne Countries, może mieć maksymalnie 3 znaki",
    )]
    protected string $countryId;

    public function getCountryId(): string
    {
        return $this->countryId;
    }

    public function setCountryId(string $countryId): self
    {
        $this->countryId = $countryId;

        return $this;
    }
}
