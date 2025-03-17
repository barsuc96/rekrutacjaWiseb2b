<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Country;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Service\Merge\MergeService;
use Wise\I18n\Domain\Country\Country;
use Wise\I18n\Domain\Country\CountryRepositoryInterface;
use Wise\I18n\Service\Country\Interfaces\ModifyCountryServiceInterface;

class ModifyCountryService implements ModifyCountryServiceInterface
{
    public function __construct(
        protected readonly MergeService $mergeService,
        private readonly CountryRepositoryInterface $repository,
    ) {}

    public function __invoke(CommonModifyParams $countryServiceDto): CommonModifyParams
    {
        $newCountryData = $countryServiceDto->read();
        $id = $newCountryData['id'] ?? null;
        $idExternal = $newCountryData['idExternal'] ?? null;

        if ($id) {
            $country = $this->repository->findOneBy(['id' => $id]);
        } elseif ($idExternal) {
            $country = $this->repository->findOneBy(['idExternal' => $idExternal]);
        }

        if (!isset($country) || !($country instanceof Country)) {
            throw new ObjectNotFoundException('Obiekt w bazie nie istnieje');
        }

        $this->mergeService->merge($country, $newCountryData, $countryServiceDto->getMergeNestedObjects());

        $country->validate();

        $country = $this->repository->save($country);

        ($resultDTO = new CommonModifyParams())->write($country);

        return $resultDTO;
    }
}
