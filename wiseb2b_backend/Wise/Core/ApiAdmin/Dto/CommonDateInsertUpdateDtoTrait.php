<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use DateTimeInterface;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

trait CommonDateInsertUpdateDtoTrait
{
    #[OA\Property(
        description: 'Data dodania',
        example: '2023-01-01 00:00:01',
    )]
    protected ?DateTimeInterface $sysInsertDate;

    #[OA\Property(
        description: 'Data ostatniej modyfikacji',
        example: '2023-01-01 00:00:01',
    )]
    protected ?DateTimeInterface $sysUpdateDate;

    public function getSysInsertDate(): ?DateTimeInterface
    {
        return $this->sysInsertDate;
    }

    public function setSysInsertDate(?DateTimeInterface $sysInsertDate): self
    {
        $this->sysInsertDate = $sysInsertDate;

        return $this;
    }

    public function getSysUpdateDate(): ?DateTimeInterface
    {
        return $this->sysUpdateDate;
    }

    public function setSysUpdateDate(?DateTimeInterface $sysUpdateDate): self
    {
        $this->sysUpdateDate = $sysUpdateDate;

        return $this;
    }
}
