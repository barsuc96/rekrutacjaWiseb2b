<?php

namespace Wise\User\Domain\User;

use Exception;
use RuntimeException;

class UserPasswordGenerator
{
    public const ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    public const ALPHABET_LENGTH = 62; // długość alfabetu
    public const PASSWORD_LENGTH = 8; // domyślna długość generowanego hasła

    /**
     * Metoda generuje losowy ciąg znaków z przeznaczeniem jako hasło użytkownika
     * @throws Exception
     */
    public function getRandomPassword(int $length = self::PASSWORD_LENGTH): string
    {
        if ($length >= self::ALPHABET_LENGTH) {
            throw new RuntimeException(
                'Maksymalna długość generowanego hasła to ' . self::ALPHABET_LENGTH . ' znaki. Próbowano wygenerować ' . $length
            );
        }

        $pass = '';

        // generowanie losowego ciągu znaków
        for ($i = 0; $i < $length; $i++) {
            $pass .= self::ALPHABET[random_int(0, self::ALPHABET_LENGTH - 1)];
        }

        return $pass;
    }
}