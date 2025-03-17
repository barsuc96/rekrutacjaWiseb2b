<?php

namespace Wise\I18n\Service\Country;

use Wise\Core\Dto\AbstractGetEntityDetailsParams;

class GetCountryDetailsParams extends AbstractGetEntityDetailsParams
{
    public const ADDITIONAL_DATA_TYPES = [
    ];

    private ?int $countryId = null;
    private ?string $countryIdExternal = null;

    /**
     * @return int|null
     */
    public function getCountryId(): ?int
    {
        return $this->countryId;
    }

    /**
     * @param int|null $countryId
     */
    public function setCountryId(?int $countryId): self
    {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountryIdExternal(): ?string
    {
        return $this->countryIdExternal;
    }

    /**
     * @param string|null $countryIdExternal
     */
    public function setCountryIdExternal(?string $countryIdExternal): self
    {
        $this->countryIdExternal = $countryIdExternal;
        return $this;
    }
}