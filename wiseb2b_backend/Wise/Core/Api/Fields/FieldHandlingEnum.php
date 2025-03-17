<?php

declare(strict_types=1);

namespace Wise\Core\Api\Fields;

/**
 * Enum określa jak ma zostać customowe pole obsłużone
 * Dodaje się w FieldMapping dla pól, które nie są bezpośrednio powiązane z encją
 */
enum FieldHandlingEnum
{
    /**
     * Pole może zostać uzupełnione tylko manualnie, ale nie zostanie wykorzystane podczas budowania DTO odpowiedzi
     * Wykorzystywane w sytuacji, kiedy mamy w responseDto zadeklarowane pole ale nie chcemy aby były pobierane z encji (bo np takie pole nie istnieje)
     * i nie chcemy go obsługiwać w metodzie transformacji ale za pomocą uzupełnienia DTO za pomocą metody fillResponseDto
     */
    case HANDLE_ONLY_BY_FILL_RESPONSE_DTO;

    /**
     * Pole będzie uzupełniane w metodzie transformacji po pobraniu danych z bazy i zostanie zwrócone w odpowiedzi
     * Wykorzystywane np. jeśli zwracamy status encji w formie encji a chcemy zwrócić wartość tzw. Formatted czyli przetłumaczoną aby wyświetlić w tabeli
     * np. status = 1, statusFormatted = 'Aktywny'
     */
    case HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE;
}
