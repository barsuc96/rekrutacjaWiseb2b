<?php

namespace Wise\User\Repository\Json;

use Wise\Core\Repository\AbstractJsonRepository;
use Wise\User\Domain\CountryCode\CountryCode;
use Wise\User\Domain\CountryCode\CountryCodeRepositoryInterface;

class CountryCodeRepository extends AbstractJsonRepository implements CountryCodeRepositoryInterface
{
    protected const ENTITY_CLASS = CountryCode::class;

    public function findByQueryFilters(array $queryFilters, array $orderBy = null, $limit = null, $offset = null): array
    {
        $result = [];
        $countryCodes =  $this->getJsonFileData();
        $class = self::ENTITY_CLASS;

        foreach ($countryCodes as $countryCode){
            $result[] = new $class(strtolower($countryCode['code']), $countryCode['name']);
        }

        return $result;
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?CountryCode
    {
        $data =  $this->getJsonFileData();
        $class = self::ENTITY_CLASS;

        $filteredArray = array_filter($data, function ($object) use ($criteria) {
            foreach ($criteria as $key => $value) {
                if (!isset($object[$key]) || $object[$key] !== $value) {
                    return false;
                }
            }
            return true;
        });

        if (!empty($filteredArray)) {
            $element = reset($filteredArray);
            return new $class(strtolower($element['code']), $element['name']);
        }

        return null;
    }

    protected function prepareData(): array
    {
        $result = [];
        $countryCodes =  $this->getJsonFileData();

        foreach ($countryCodes as $countryCode){
            $result[] = [
                'code' => $countryCode['code'],
                'name' => $countryCode['name']
            ];
        }

        return $result;
    }
}
