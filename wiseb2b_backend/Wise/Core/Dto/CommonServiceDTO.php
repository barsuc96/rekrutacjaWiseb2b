<?php

declare(strict_types=1);

namespace Wise\Core\Dto;

use Wise\Core\DataTransformer\CommonDataTransformer;

/**
 * Uniwersalna klasa parametrów i wyniku
 */
class CommonServiceDTO implements ServiceDTOInterface
{

    /**
     * Dane, dzięki przechowywaniu wyniki w tablicy umożliwiamy dowolną modyfikację we wdrożeniu
     * @var array|null
     */
    protected ?array $data = [];

    /**
     * Metoda umożliwia zapisanie danych do DTO (tablicowych jak i obiektów)
     * @param mixed $data
     * @param array|null $fieldMapping
     * @return void
     */
    public function write(mixed $data, ?array $fieldMapping = []): void
    {
        if (is_array($data)) {
            foreach ($data as $object) {
                $this->data[] = CommonDataTransformer::transformToArray($object, $fieldMapping);
            }
        } else {
            $this->data = CommonDataTransformer::transformToArray($data, $fieldMapping);
        }
    }

    /**
     * Metoda umożliwia zapisanie danych do DTO bezpośrednio w postaci tablicy asocjacyjnej
     * @param array $data
     * @param array|null $fieldMapping
     * @return $this
     */
    public function writeAssociativeArray(array $data, ?array $fieldMapping = []): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Metoda umożliwia zapisanie danych do DTO bezpośrednio w postaci tablicy asocjacyjnej (dodaje do istniejących danych)
     * @param array $data
     * @return void
     */
    public function mergeWithAssociativeArray(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }


    /**
     * Metoda umożliwia zapisanie danych do DTO (tablicowych jak i obiektów) w postaci tablicy asocjacyjnej (dodaje do istniejących danych)
     * @param array $fieldMapping
     * @param bool $flip
     * @return void
     */
    protected function map(array $fieldMapping = [], bool $flip = false): void
    {
        if (count($fieldMapping)){
            if ($flip) {$fieldMapping = array_flip($fieldMapping);}
            $this->data = CommonDataTransformer::transformToArray($this->data, $fieldMapping);
        }
    }

    /**
     * Metoda umożliwia odczytanie danych z DTO
     * @param array|null $fieldMapping
     * @return array|null
     */
    public function read(?array $fieldMapping = []): ?array
    {
        if ($fieldMapping) {
            return CommonDataTransformer::transformToArray($this->data, $fieldMapping);
        } else {
            return $this->data;
        }
    }
}
