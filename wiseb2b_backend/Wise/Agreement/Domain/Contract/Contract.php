<?php

namespace Wise\Agreement\Domain\Contract;

use Wise\Agreement\Domain\Contract\Event\ContractContentHasChangedEvent;
use Wise\Agreement\Domain\Contract\Event\ContractHasChangedEvent;
use Wise\Agreement\Domain\Contract\Event\ContractStatusHasChangedEvent;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\Translations;
use Symfony\Component\Validator\Constraints as Assert;

class Contract extends AbstractEntity
{
    /**
     * Id zewnętrzne (z systemu klienta)
     * @var string|null
     */
    protected ?string $idExternal;

    /**
     * Stopień wymagalności
     * Korzystaj z: Wise\Agreement\Domain\Contract\Enum\ContractRequirement
     * @var int
     *
     */
    #[Assert\NotBlank]
    protected ?int $requirement;

    /**
     * Oddziaływanie (na kogo oddziałowuje umowa np. na klienta, na zamówienie itd)
     * Korzystaj z: Wise\Agreement\Domain\Contract\Enum\ContractImpact
     * @var int
     */
    #[Assert\NotBlank]
    protected ?int $impact;

    /**
     * Kontekst prośby (w jakich miejscach wyświetlać prośbę)
     * Oddzielone elementy za pomocą znaku ";"
     * Korzystaj z: Wise\Agreement\Domain\Contract\Enum\ContractContext
     * @var string
     */
    #[Assert\NotBlank]
    protected ?string $contexts;

    /**
     * Symbol - unikalny identyfikator, aby można było odwołać się do konkretnej umowy w kodzie
     * @var string
     */
    protected ?string $symbol;

    /**
     * Typ umowy
     * Korzystaj z: Wise\Agreement\Domain\Contract\Enum\ContractType
     * @var string
     */
    #[Assert\NotBlank]
    protected ?string $type;

    /**
     * Role użytkowników, których dotyczy umowa
     * Oddzielone role za pomocą znaku ";"
     * @var string|null
     */
    protected ?string $roles = null;

    /**
     * Status
     * Korzystaj z: Wise\Agreement\Domain\Contract\Enum\ContractStatus
     * @var int
     */
    #[Assert\NotBlank]
    protected ?int $status;

    /**
     * Nazwa umowy
     * @var Translations|null
     */
    protected ?Translations $name = null;

    /**
     * Treść świadczenia do umowy — treść html, krótka, samo oświadczenie o akceptacji — wyświetla na froncie klienta.
     * @var Translations|null
     */
    protected ?Translations $testimony = null;

    /**
     * Treść umowy (content)
     * @var Translations|null
     */
    protected ?Translations $content = null;

    /**
     * Data obowiązywania umowy od
     * @var \DateTimeInterface|null
     */
    protected ?\DateTimeInterface $fromDate = null;

    /**
     * Data obowiązywania umowy do
     * @var \DateTimeInterface|null
     */
    protected ?\DateTimeInterface $toDate = null;

    /**
     * Data ustawienia umowy na status "deprecated"
     * @var \DateTimeInterface|null
     */
    protected ?\DateTimeInterface $deprecatedDate = null;

    /**
     * Data ustawienia umowy na status "inActive"
     * @var \DateTimeInterface|null
     */
    protected ?\DateTimeInterface $inActiveDate = null;

    public function __construct()
    {
    }

    public function getRequirement(): ?int
    {
        return $this->requirement;
    }

    public function setRequirement(?int $requirement): self
    {
        $this->requirement = $requirement;

        return $this;
    }

    public function getImpact(): ?int
    {
        return $this->impact;
    }

    public function setImpact(?int $impact): self
    {
        $this->impact = $impact;

        return $this;
    }

    public function getContexts(): string
    {
        return $this->contexts;
    }

    public function setContexts(?string $contexts): self
    {
        $this->contexts = $contexts;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(?string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        if($this->isInitialized('status') && $this->status !== $status) {
            DomainEventManager::instance()->post(new ContractStatusHasChangedEvent($this->getId()));
        }

        $this->status = $status;

        return $this;
    }

    public function getName(): ?Translations
    {
        if ($this->name === null) {
            $this->name = new Translations();
        }

        return $this->name;
    }

    public function setName(?Translations $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?Translations
    {
        if ($this->content === null) {
            $this->content = new Translations();
        }

        return $this->content;
    }

    public function setContent(?Translations $content): self
    {
        if($this->content !== $content) {
            $this->content = $content;

            DomainEventManager::instance()->post(new ContractContentHasChangedEvent($this->getId(), $this->content, $content));
        }

        return $this;
    }

    public function getTestimony(): ?Translations
    {
        return $this->testimony;
    }

    public function setTestimony(?Translations $testimony): self
    {
        $this->testimony = $testimony;

        return $this;
    }



    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(?\DateTimeInterface $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?\DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(?\DateTimeInterface $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

    public function getDeprecatedDate(): ?\DateTimeInterface
    {
        return $this->deprecatedDate;
    }

    public function setDeprecatedDate(?\DateTimeInterface $deprecatedDate): self
    {
        $this->deprecatedDate = $deprecatedDate;

        return $this;
    }

    public function getInActiveDate(): ?\DateTimeInterface
    {
        return $this->inActiveDate;
    }

    public function setInActiveDate(?\DateTimeInterface $inActiveDate): self
    {
        $this->inActiveDate = $inActiveDate;

        return $this;
    }

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new ContractHasChangedEvent($this->getId()));
    }
}
