<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Country;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\I18n\Domain\Country\Country;
use Wise\I18n\Domain\Country\CountryRepositoryInterface;
use Wise\I18n\Service\Country\Interfaces\AddCountryServiceInterface;
use Wise\I18n\Service\Country\Interfaces\AddOrModifyCountryServiceInterface;
use Wise\I18n\Service\Country\Interfaces\ModifyCountryServiceInterface;

class AddOrModifyCountryService implements AddOrModifyCountryServiceInterface
{
    public function __construct(
        private readonly CountryRepositoryInterface $repository,
        private readonly ModifyCountryServiceInterface $modifyService,
        private readonly AddCountryServiceInterface $addService
    ) {}

    /**
     * @throws ObjectNotFoundException
     */
    public function __invoke(CommonModifyParams $countryServiceDto): CommonModifyParams
    {
        $data = $countryServiceDto->read();
        $country = $this->findCountry($data);

        if (true === $country instanceof Country) {
            return ($this->modifyService)($countryServiceDto);
        }

        return ($this->addService)($countryServiceDto);
    }

    /**
     * @throws ObjectNotFoundException
     */
    private function findCountry(array $data): ?Country
    {
        $country = null;
        $id = $data['id'] ?? null;
        $idExternal = $data['idExternal'] ?? null;

        if (null !== $id) {
            $country = $this->repository->findOneBy(['id' => $id]);
            if (false !== $country instanceof Country) {
                throw new ObjectNotFoundException('Nie znaleziono Country o id: ' . $id);
            }

            return $country;
        }

        if (null !== $idExternal) {
            $country = $this->repository->findOneBy(['idExternal' => $idExternal]);
        }

        return $country;
    }
}
