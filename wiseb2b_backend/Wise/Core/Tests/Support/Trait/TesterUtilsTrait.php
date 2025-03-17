<?php

namespace Wise\Core\Tests\Support\Trait;

use Faker\Factory;
use Wise\Core\Tests\Support\Enum\TypeApiEnum;

trait TesterUtilsTrait
{

    /**
     * Sprawdza czy w obiektach znajduje się element o id
     * @param array $response
     * @param string $id
     * @param string $nameParameter czasem jest tak, że zamiast objects jest items
     * @return void
     */
    public function seeObjectWithID(
        array $response,
        string $id,
        string $columnKeyId = 'id',
        string $nameParameter = 'objects'
    ): void {
        $this->assertTrue(in_array($id, array_column($response[$nameParameter], $columnKeyId)));
    }

    /**
     * Sprawdza czy w obiektach nie znajduje się element o id
     * @param array $response
     * @param string $id
     * @param string $nameParameter czasem jest tak, że zamiast objects jest items
     * @return void
     */
    public function dontSeeObjectWithID(array $response, string $id, string $nameParameter = 'objects'): void
    {
        $this->assertFalse(in_array($id, array_column($response[$nameParameter], 'id')));
    }

    /**
     * Pobiera element o id z response
     * @param array $response
     * @param string $id
     * @param string $nameParameterObjects czasem jest tak, że zamiast objects jest items
     * @return array|null
     */
    public function grabObjectWithId(
        array $response,
        string $id,
        string $columnKeyId = 'id',
        string $nameParameterObjects = 'objects'
    ): ?array {
        $objects = $response[$nameParameterObjects];
        return array_values(
            array_filter($objects, function ($object) use ($id, $columnKeyId) {
                return $object[$columnKeyId] == $id ?? null;
            })
        )[0] ?? null;
    }

    /**
     * Sprawdza czy w response znajduje się obiekt o podanym id w parametrach
     * I dodatkowo weryfikuje, czy znajduja się tam elementy z odpowiednimi wartościami
     * @param array $response
     * @param string $id - identyfikator obiektu
     * @param array $elementToCheck - tablica która weryfikuje czy dane elementy znajduja się wewnatrz response
     * @param string $columnKeyId - nazwa kolumny identyfikujacej rekord
     * @param string $nameParameterObjects czasem jest tak, że zamiast objects jest items
     * @return void
     */
    public function seeObjectWithElementsInResponse(
        array $response,
        string $id,
        array $elementToCheck,
        string $columnKeyId = 'id',
        string $nameParameterObjects = 'objects'
    ): array {
        $object = $this->grabObjectWithId($response, $id, $columnKeyId, $nameParameterObjects);
        if (is_null($object)) {
            // Jeśli jest null, czyli nie istnieje taki obiekt to chcemy aby test nie przeszedł
            $this->assertNotNull($object, 'Nie istnieje obiekt o ' . $columnKeyId . ': "' . $id . '"');
        }

        foreach ($elementToCheck as $element => $value) {
            $issetKey = array_key_exists($element, $object);
            $this->assertTrue($issetKey, 'W obiekcie nie ma elementu ' . $element);
            if ($issetKey) {
                $this->assertEquals(
                    $object[$element],
                    $value,
                    'Otrzymany element ' . $element . ' w response różni się od tego przekazanego'
                );
            }
        }

        return $object;
    }

    /**
     * Sprawdza czy w response znajduje się obiekt o podanym id w parametrach
     * I dodatkowo weryfikuje, czy znajduja się tam elementy z odpowiednimi wartościami
     * @param array $response
     * @param string $id - identyfikator obiektu
     * @param array $elementToCheck - tablica która weryfikuje czy dane elementy znajduja się wewnatrz response
     * @param string $columnKeyId - nazwa kolumny identyfikujacej rekord
     * @param string $nameParameterObjects czasem jest tak, że zamiast objects jest items
     * @return void
     */
    public function seeThatObjectHaveCorrectTranslationInResponse(
        array $response,
        string $id,
        array $elementToCheck,
        string $columnKeyId = 'id',
        string $nameParameterObjects = 'objects'
    ): void {
        $object = $this->grabObjectWithId($response, $id, $columnKeyId, $nameParameterObjects);
        if (is_null($object)) {
            // Jeśli jest null, czyli nie istnieje taki obiekt to chcemy aby test nie przeszedł
            $this->assertNotNull($object, 'Nie istnieje obiekt o id: "' . $id . '"');
        }

        foreach ($elementToCheck as $key => $value) {
            $issetKey = array_key_exists($key, $object);
            $this->assertTrue($issetKey, 'W obiekcie nie ma elementu ' . $key);
            if ($issetKey) {
                usort($value, fn($a, $b) => $a['language'] <=> $b['language']);
                usort($object[$key], fn($a, $b) => $a['language'] <=> $b['language']);

                // Sprawdza czy otrzymane translacje są takie same
                $this->assertEquals($object[$key], $value);
            }
        }
    }

    public function seeThatArrayHasCorrectValueInSubArrays(
        array $elementToCheck,
        array $data,
        string $nameColumn = 'language'
    ): void {
        foreach ($elementToCheck as $element) {
            $result = false;
            foreach ($data as $subArray) {
                if (isset($subArray[$nameColumn])) {
                    if ($subArray[$nameColumn] === $element) {
                        $result = true;
                        break;
                    }
                }
            }

            if ($result === false) {
                $this->assertTrue($result, 'Element [' . $nameColumn . '] nie pokrywa się ze sobą');
            }
        }
        $this->assertTrue($result);
    }

    public function fake($lang = 'pl_PL'): \Faker\Generator
    {
        return Factory::create($lang);
    }

    /**
     * Zwraca gotową tablice z translacjami
     * @param int $maxNumberCharacter - jak długi ma wygenerować tekst
     * @param bool $all - false - wygeneruje losowane translacji w odpowiednim przedziale
     * @param int $minQuantityElements
     * @param int $maxQuantityElements
     * @return array
     */
    public function prepareTranslation(
        int $maxNumberCharacter = 50,
        bool $all = true,
        int $minQuantityElements = 2,
        int $maxQuantityElements = 3,
        string $translationColumn = 'translation'
    ): array {
        $translation = [];
        $language = ['de_DE', 'en_US', 'pl_PL'];
        $countLanguage = count($language);
        if (!$all) {
            $countLanguage = rand($minQuantityElements, $maxQuantityElements);
        }

        for ($i = 0; $i < $countLanguage; $i++) {
            $chosenLanguage = $language[$i];
            $translation[] = [
                'language' => substr(strtolower($chosenLanguage), 3, 5),
                $translationColumn => 
                    $this->fake($chosenLanguage)->realText($maxNumberCharacter)
            ];
        }
        return $translation;
    }

    public function generateId(string $prefix): string
    {
        return $prefix . rand(10000, 9999999);
    }

    /**
     * Weryfikuje czy wartość jest równa chociaż jednemu elementowi z tablicy
     */
    public function assertEqualsAtLeastOneElementFromArray($expected, array $actual): void
    {
        $contain = false;
        foreach ($actual as $element) {
            if ($element === $expected) {
                $contain = true;
                break;
            }
        }
        $this->assertTrue($contain);
    }

    /**
     * Zwraca poprawny kod ean 13
     * @return string
     */
    public function generateEAN13(): string
    {
        return '978020137962';
    }

}
