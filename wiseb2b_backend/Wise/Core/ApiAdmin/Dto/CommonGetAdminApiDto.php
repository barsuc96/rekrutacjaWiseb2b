<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

/**
 * Obiekt zawierający standardowe pola do filtrowania użyte w każdym zapytaniu GET w ApiAdmin Api
 * @deprecated zastąpione przez \Wise\Core\ApiAdmin\Dto\CommonDeleteAdminApiParametersDto
 */
abstract class CommonGetAdminApiDto extends AbstractDto
{
    #[OA\Property(
        description: 'Ilość na stronie',
        example: 10,
    )]
    #[Assert\Type(type: 'integer', message: 'Musisz podać liczbową wartość ilości na stronie')]
    #[Assert\GreaterThan(value: 0, message: 'Ilość na stronie musi być większa od {{ value }}')]
    protected int $limit = 10;

    #[OA\Property(
        description: 'Strona',
        example: 1,
    )]
    #[Assert\Type(type: 'integer', message: 'Musisz podać liczbową wartość numeru strony')]
    #[Assert\GreaterThan(value: 0, message: 'Numer strony musi być większy od {{ value }}')]
    protected int $page = 1;

    #[OA\Property(
        description: 'Data modyfikacji od',
        example: '2022-09-30 00:00:00',
    )]
    #[Assert\DateTime(message: 'Musisz podać poprawną datę według standardu Y-m-d H:i:s')]
    protected string $changeDateFrom;

    #[OA\Property(
        description: 'Data modyfikacji do',
        example: '2022-09-30 23:59:59',
    )]
    #[Assert\DateTime(message: 'Musisz podać poprawną datę według standardu Y-m-d H:i:s')]
    protected string $changeDateTo;


    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getChangeDateFrom(): string
    {
        return $this->changeDateFrom;
    }

    public function setChangeDateFrom(string $changeDateFrom): self
    {
        $this->changeDateFrom = $changeDateFrom;
        return $this;
    }

    public function getChangeDateTo(): string
    {
        return $this->changeDateTo;
    }

    public function setChangeDateTo(string $changeDateTo): self
    {
        $this->changeDateTo = $changeDateTo;
        return $this;
    }
}
