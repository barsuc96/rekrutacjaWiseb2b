<?php

namespace Wise\Core\Api\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

trait CommonParameterListTrait
{
    #[OA\Query(description: 'Strona', example: 1)]
    #[Assert\Type(type: 'integer', message: 'Musisz podać liczbową wartość numeru strony')]
    #[Assert\GreaterThan(value: 0, message: 'Numer strony musi być większy od {{ value }}')]
    protected int $page;

    #[OA\Query(description: 'Ilość na stronie', example: 10)]
    #[Assert\Type(type: 'integer', message: 'Musisz podać liczbową wartość ilości na stronie')]
    #[Assert\GreaterThan(value: 0, message: 'Ilość na stronie musi być większa od {{ value }}')]
    protected int $limit;

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
