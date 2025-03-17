<?php

declare(strict_types=1);

namespace Wise\Service\Domain\Service;

use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\Object\TranslationsHelper;
use Wise\Core\Model\Translations;
use Wise\Service\Domain\Service\Events\ServiceHasChangedEvent;

/**
 * Encja reprezentująca dodatkowe usługi
 */
class Service extends AbstractEntity
{
    /**
     * Identyfikator z zewnętrznego systemu (np. CRM)
     * @var string|null
     */
    protected ?string $idExternal = null;

    /**
     * Typ usługi
     * @var string|null
     */
    protected ?string $type = null;

    /**
     * Metoda obliczania kosztów (np stała, bądź procentowa z wartości koszyka)
     * @var int|null
     */
    protected ?int $costCalcMethod = null;

    /**
     * Parametr metody obliczania kosztów
     * @var float|null
     */
    protected ?float $costCalcParam = null;

    /**
     * Procent podatku
     * @var float|null
     */
    protected ?float $taxPercent = null;

    /**
     * Nazwa usługi
     * @var Translations|null
     */
    protected ?Translations $name = null;

    /**
     * Opis usługi
     * @var Translations|null
     */
    protected ?Translations $description = null;

    /**
     * Nazwa sterownika (m.in wykorzystywane do wyliczenia ostatecznej kwoty)
     * @var string|null
     */
    protected ?string $driverName = null;

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?Translations
    {
        return $this->name;
    }

    public function setName(null|Translations|array $name): self
    {
        $this->name = TranslationsHelper::convert($name);

        return $this;
    }

    public function getDescription(): ?Translations
    {
        return $this->description;
    }

    public function setDescription(null|Translations|array $description): self
    {
        $this->description = TranslationsHelper::convert($description);

        return $this;
    }

	public function getCostCalcMethod(): ?int {
		return $this->costCalcMethod;
	}

	public function setCostCalcMethod(?int $costCalcMethod): self {
		$this->costCalcMethod = $costCalcMethod;
		return $this;
	}

	public function getCostCalcParam(): ?float {
		return $this->costCalcParam;
	}

	public function setCostCalcParam(?float $costCalcParam): self {
		$this->costCalcParam = $costCalcParam;
		return $this;
	}

    public function getDriverName()
    {
        return $this->driverName;
    }

    public function setDriverName($driverName)
    {
        $this->driverName = $driverName;

        return $this;
    }

    public function getTaxPercent(): ?float
    {
        return $this->taxPercent;
    }

    public function setTaxPercent(?float $taxPercent): self
    {
        $this->taxPercent = $taxPercent;

        return $this;
    }

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new ServiceHasChangedEvent($this->getId()));
    }
}
