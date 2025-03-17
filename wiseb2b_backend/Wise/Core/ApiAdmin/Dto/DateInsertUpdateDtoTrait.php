<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use OpenApi\Attributes as OA;

/**
 * Trait z datą dodania i modyfikacji
 * @deprecated Nie używać z mechanizmami przygotowywania DTO z AdminApiShareMethodsHelper. Użyć wtedy CommonDateInsertUpdateDtoTrait
 */
trait DateInsertUpdateDtoTrait
{
    #[OA\Property(
        description: 'Data dodania',
        example: '2023-01-01 00:00:01',
    )]
    protected ?string $sysInsertDate;

    #[OA\Property(
        description: 'Data ostatniej modyfikacji',
        example: '2023-01-01 00:00:01',
    )]
    protected ?string $sysUpdateDate;

    public function getSysInsertDate(): ?string
    {
        return $this->sysInsertDate;
    }

    public function setSysInsertDate(?string $sysInsertDate): self
    {
        $this->sysInsertDate = $sysInsertDate;

        return $this;
    }

    public function getSysUpdateDate(): ?string
    {
        return $this->sysUpdateDate;
    }

    public function setSysUpdateDate(?string $sysUpdateDate): self
    {
        $this->sysUpdateDate = $sysUpdateDate;

        return $this;
    }
}
