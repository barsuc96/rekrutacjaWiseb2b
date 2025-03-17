<?php

namespace Wise\Agreement\Domain\ContractTypeDictionary;

use Wise\Agreement\Domain\ContractTypeDictionary\Event\ContractTypeDictionaryHasChangedEvent;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;

/**
 * Klasa reprezentująca słowniki typów dla umów
 */
class ContractTypeDictionary extends AbstractEntity
{
    /**
     * Id zewnętrzne (z systemu klienta - według architektury każda encja powinna posiadać takie pole)
     * @var string|null
     */
    protected ?string $idExternal;

    /**
     * Nazwa typu (wyświetlana aby łatwiej było ją odnaleźć w panelu administracyjnym)
     * @var string|null
     */
    protected ?string $name;

    /**
     * Symbol typu
     * @var string|null
     */
    protected ?string $symbol;

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new ContractTypeDictionaryHasChangedEvent($this->getId()));
    }
}
