<?php

declare(strict_types=1);

namespace Wise\Core\Exception;

/**
 * Wyjątek, który stosujemy, gdy uważamy, że dana funkcja dziedzicząca po innej nie powinna implementować danej funkcji,
 * np. nasze repozytorium dziedziczy po {@see \Wise\Core\Repository\AbstractRepository}, ale uważamy, że metoda `find()`
 * nigdy nie powinna zostać wykorzystana w danym kontekście.
 *
 * Wyjątek dziedziczy po {@see \RuntimeException} ze względu na sposób wywołania. Jeżeli programista nieświadomie użył
 * metody, która nie powinna zostać wywołania, to powinien się o tym dowiedzieć podczas weryfikacji (runtime).
 */
final class NotImplementedException extends \RuntimeException
{
    /**
     * @param string $class Zawsze przekaż "__CLASS__"
     * @param string $method Zawsze przekaż "__METHOD__"
     * @param string $message Dodaj wiadomość, dlaczego metoda nie jest zaimplementowana
     */
    public function __construct(string $class, string $method, string $message)
    {
        parent::__construct(sprintf('%s::%s not implemented - %s', $class, $method, $message));
    }
}
