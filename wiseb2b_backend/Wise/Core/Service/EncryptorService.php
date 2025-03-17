<?php

namespace Wise\Core\Service;

/**
 * Serwis obsługujący szyfrowanie danych dwukierunkowe
 */
class EncryptorService
{
    /**
     * Metoda pozwala zaszyfrować dane
     * @param string $data
     * @param string $key
     * @return string
     */
    public static function encrypt(string $data, string $key): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

        return base64_encode($iv . $encrypted);
    }

    /**
     * Pozwala odszyfrować dane
     * @param string $data
     * @param string $key
     * @return string
     */
    public static function decrypt(string $data, string $key): string
    {
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }
}
