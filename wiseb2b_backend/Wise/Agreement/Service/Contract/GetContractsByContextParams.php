<?php

namespace Wise\Agreement\Service\Contract;

use Wise\Core\Dto\CommonServiceDTO;

class GetContractsByContextParams extends CommonServiceDTO
{
    /**
     * Kontekst, dla którego ma pobrać zgody
     * @var string|null
     */
    private ?string $context = null;

    /**
     * Czy ma zwrócić tylko kontrakty, które muszą być zaakceptowane
     * @var bool|null
     */
    private ?bool $onlyMustAccept = false;

    /**
     * Identyfikator koszyka
     * @var int|null
     */
    private ?int $cartId = null;

    /**
     * Strona
     * @var int|null
     */
    private ?int $page = null;

    /**
     * Limit
     * @var int|null
     */
    private ?int $limit = null;

    /**
     * Tablica pomijanych kontekstów
     * @var array|null
     */
    private ?array $skipContexts = [];

    /**
     * Tablica pomijanych zgód posiadających przekazane oddziaływanie
     * Np. możemy chcieć zwrócić wszystkie zgody, które nie oddziałowują na zamówienia
     * @var array|null
     */
    private ?array $skipImpact = [];

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getOnlyMustAccept(): ?bool
    {
        return $this->onlyMustAccept;
    }

    public function setOnlyMustAccept(?bool $onlyMustAccept): self
    {
        $this->onlyMustAccept = $onlyMustAccept;

        return $this;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getCartId(): ?int
    {
        return $this->cartId;
    }

    public function setCartId(?int $cartId): self
    {
        $this->cartId = $cartId;

        return $this;
    }

    public function getSkipContexts(): ?array
    {
        return $this->skipContexts;
    }

    public function setSkipContexts(?array $skipContexts): self
    {
        $this->skipContexts = $skipContexts;

        return $this;
    }

    public function getSkipImpact(): ?array
    {
        return $this->skipImpact;
    }

    public function setSkipImpact(?array $skipImpact): self
    {
        $this->skipImpact = $skipImpact;

        return $this;
    }


}
