<?php

namespace Wise\Agreement\ApiAdmin\Dto\Contract;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Ignore;
use Wise\Core\Dto\AbstractDto;

class ContractTranslationDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id umowy',
        example: 'XYZ-ABC-1234',
    )]
    #[Ignore]
    protected string $contractId;

    #[OA\Property(
        description: 'Język tłumaczenia',
        example: 'pl',
    )]
    protected string $language;

    #[OA\Property(
        description: 'Tłumaczenie nazwy produktu',
        example: 'Umowa - regulamin 2024.11.24',
    )]
    protected string $translation;

    public function getContractId(): string
    {
        return $this->contractId;
    }

    public function setContractId(string $contractId): self
    {
        $this->contractId = $contractId;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }

    public function setTranslation(string $translation): self
    {
        $this->translation = $translation;
        return $this;
    }
}
