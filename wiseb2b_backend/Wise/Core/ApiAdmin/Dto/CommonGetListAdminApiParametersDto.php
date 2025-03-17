<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

/**
 * ## Klasa pomocnicza dla DTO parametrów w ADMINAPI - GET LIST
 * Podstawowe pola wymagane do poprawnego działania endpointów zwracających listę obiektów
 */
class CommonGetListAdminApiParametersDto extends AbstractDto
{
    #[OA\Property(
        description: 'Ilość na stronie',
        example: 10,
    )]
    #[Assert\Type(type: 'integer', message: 'Musisz podać liczbową wartość ilości na stronie')]
    #[Assert\GreaterThan(value: 0, message: 'Ilość na stronie musi być większa od {{ value }}')]
    //TODO: te pola powinny być nullable, i bez wartości domyśłnych.
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

    #[OA\Property(
        description: 'Czy zwrócić tylko nieprzetworzone obiekty (gdzie id jest równe null)',
        example: false,
    )]
    protected bool $isNotProcessed;

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

    public function isNotProcessed(): bool
    {
        return $this->isNotProcessed;
    }

    public function setIsNotProcessed(bool $isNotProcessed): self
    {
        $this->isNotProcessed = $isNotProcessed;

        return $this;
    }
}
