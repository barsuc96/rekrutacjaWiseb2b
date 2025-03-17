<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto\CommonParameters;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ## Klasa bazowa dla DTO parametrów w UIAPI - GET LIST
 * Podstawowe pola wymagane do poprawnego działania endpointów zwracających listę obiektów
 */
class CommonGetListUiApiParametersDto extends CommonParametersDto
{

    #[OA\Property(description: 'Strona', example: 1)]
    #[Assert\Type(type: 'integer', message: 'Musisz podać liczbową wartość numeru strony')]
    #[Assert\GreaterThan(value: 0, message: 'Numer strony musi być większy od {{ value }}')]
    protected int $page = 1;

    #[OA\Property(description: 'Ilość na stronie', example: 10)]
    #[Assert\Type(type: 'integer', message: 'Musisz podać liczbową wartość ilości na stronie')]
    #[Assert\GreaterThan(value: 0, message: 'Ilość na stronie musi być większa od {{ value }}')]
    protected int $limit = 10;

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
