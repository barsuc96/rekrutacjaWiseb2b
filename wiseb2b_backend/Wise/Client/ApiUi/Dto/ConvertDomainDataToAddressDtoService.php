<?php

namespace Wise\Client\ApiUi\Dto;

use Wise\Client\ApiUi\Dto\Interfaces\ConvertDomainDataToAddressDtoServiceInterface;

/**
 * Serwis konwertuje dane adresowe na adres obsługiwany w response.
 */
class ConvertDomainDataToAddressDtoService implements ConvertDomainDataToAddressDtoServiceInterface
{
    public function __invoke(array $serviceDtoData): array
    {
        $this->transformResponseData($serviceDtoData);

        return $serviceDtoData;
    }


    /**
     * Metoda pozwala przekształcić dane do responseDto
     * Transformuje klucze w tablicy, uwzględniając zagnieżdżone struktury danych.
     * @return void
     */
    protected function transformResponseData(array &$serviceDtoData): void
    {
        foreach ($serviceDtoData as $key => &$value) {
            if (is_array($value)) {
                $this->transformResponseData($value);
            }else{
                $this->applyTransformations($serviceDtoData, $key, $value);
            }
        }
    }

    /**
     * Zastosowuje transformacje do kluczy w danej tablicy.
     * @param array $array
     * @param $key
     * @param $value
     * @return void
     */
    protected function applyTransformations(array &$array, $key, &$value): void
    {
        switch ($key) {
            case 'houseNumber':
                $array['building'] = $value;
                unset($array[$key]);
                break;
            case 'apartmentNumber':
                $array['apartment'] = $value;
                unset($array[$key]);
                break;
        }
    }
}
