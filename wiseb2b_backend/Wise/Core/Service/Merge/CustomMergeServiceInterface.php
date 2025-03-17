<?php

namespace Wise\Core\Service\Merge;

use Wise\Core\Model\MergableInterface;

/**
 * @template T of MergableInterface
 */
interface CustomMergeServiceInterface
{
    /**
     * Zweryfikuj, czy podany obiekt może zostać obsłużony przez ten spersonalizowany serwis do obsługi łączeń.
     *
     * @param T $object Badany obiekt
     *
     * @return bool
     */
    public function supports($object): bool;

    /**
     * Dokonaj dedykowanych złączeń dla danego obiektu.
     *
     * UWAGA:
     * Zmienna $data jest przekazywana przez referencję, co oznacza, że każda modyfikacja oddziałuje na dalszy proces
     * działania MergeService. Na przykład skasowanie wszystkich elementów spowoduje zaprzestanie dalszego procesowania,
     * a zachowanie typów prostych lub obiektów dziedziczących po MergableInterface.
     *
     * @param T $object Obiekt, do którego dane mają być dołączone
     * @param array<string, mixed> $data Dane do dołączenia
     * @param bool $mergeNestedObjects Zachowaj elementy w podrzędnych tablicach i kolekcjach, jeżeli nie istnieją
     *                                 w danych do dołączenia
     *
     * @return void Wróć do MergeService i kontynuuj łączenie
     *
     * @throws \Exception W przypadku błędu
     */
    public function merge($object, array &$data, bool $mergeNestedObjects): void;
}
