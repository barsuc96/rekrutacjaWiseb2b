<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Country;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectExistsException;
use Wise\Core\Service\Merge\MergeService;
use Wise\I18n\Domain\Country\Country;
use Wise\I18n\Domain\Country\CountryRepositoryInterface;
use Wise\I18n\Service\Country\Interfaces\AddCountryServiceInterface;

class AddCountryService implements AddCountryServiceInterface
{
    public function __construct(
        protected readonly MergeService $mergeService,
        private readonly CountryRepositoryInterface $repository,
    ) {}

    public function __invoke(CommonModifyParams $countryServiceDto): CommonModifyParams
    {
        $newCountryData = $countryServiceDto->read();
        $id = $newCountryData['id'] ?? null;

        if ($this->repository->findOneBy(['id' => $id])) {
            throw new ObjectExistsException('Obiekt w bazie już istnieje');
        }

        $newCountry = new Country();
        $this->mergeService->merge($newCountry,$countryServiceDto->read());

        $newCountry->validate();

        $newCountry = $this->repository->save($newCountry);

        ($resultDTO = new CommonModifyParams())->write($newCountry);

        return $resultDTO;
    }
}
