<?php

declare(strict_types=1);

namespace Wise\Core\Dto;

use Wise\Core\Service\SummaryField;

/**
 * Abstrakcyjna klasa DTO dla serwisów zwracających podsumowanie danych
 */
abstract class AbstractGetSummaryParams extends CommonServiceDTO
{
    /**
     * Deklaracja rodzajów pól sumacyjnych obsługiwanych przez serwis,
     *
     * Odpowiada pewną okręslonej dzieńdzinie encji na których ma być wykonywana agregacja.
     * Jest pojęciem biznesowym.
     */
    public const SUMMARIES_FIELD_TYPES = [

    ];

    /**
     * Użytkownik który ma mieć dostęp do okreslonych danych
     */
    protected int $userId;

    /**
     * pola wynikowe - pojęcia biznesowe, za którymi kryje się pewien predefinoiwany filtr.
     * Nazewnictwo analogicznie/podobnie jak addidtionalDAta z getDietailsService. WIęc
     *
     * @var SummaryField[] $summaryFields
     */
    protected array $summaryFields;

    /**
     * Dodatkowe filtry dodawane do dziedziny po której jest realizowana agregacja
     */
    protected array $filters;

    public function getSummaryFields(): array
    {
        return $this->summaryFields;
    }

    public function setSummaryFields(array $summaryFields): self
    {
        $this->summaryFields = $summaryFields;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }
}
