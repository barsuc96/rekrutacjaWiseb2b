<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Country;

use JetBrains\PhpStorm\Pure;
use Psr\EventDispatcher\EventDispatcherInterface;
use Wise\Core\ApiAdmin\Service\DeprecatedAbstractRemoveService;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\I18n\Domain\Country\CountryAfterRemoveEvent;
use Wise\I18n\Domain\Country\CountryBeforeRemoveEvent;
use Wise\I18n\Domain\Country\CountryRepositoryInterface;
use Wise\I18n\Service\Country\Interfaces\ListByFiltersCountryServiceInterface;
use Wise\I18n\Service\Country\Interfaces\RemoveCountryServiceInterface;

class RemoveCountryService extends DeprecatedAbstractRemoveService implements RemoveCountryServiceInterface
{
    #[Pure]
    public function __construct(
        EventDispatcherInterface $dispatcher,
        RepositoryManagerInterface $repositoryManager,
        CountryRepositoryInterface $countryRepository,
        private readonly ListByFiltersCountryServiceInterface $listByFiltersCountryService
    ) {
        parent::__construct($dispatcher, $repositoryManager, $countryRepository);
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(CommonServiceDTO $serviceDTO, bool $continueAfterErrors = false): CommonServiceDTO
    {
        $data = $serviceDTO->read();

        // Wyciągamy id obiektów spełniających kryteria, aby je usunąć
        $countriesToDelete = ($this->listByFiltersCountryService)(
            $this->getFiltersForRemove($data),
            $this->getJoinsForRemove($data),
            ['id']
        );

        //Usuwamy znalezione kategorie
        $removedIds = $this->removeItems(
            $countriesToDelete->read(),
            CountryBeforeRemoveEvent::class,
            CountryAfterRemoveEvent::class,
            $continueAfterErrors
        );

        $result = (new CommonServiceDTO());
        $result->writeAssociativeArray($removedIds);

        return $result;
    }

    #[Pure]
    protected function getJoinsForRemove(array $data): array
    {
        $joins = [];

        //Jeśli obiekt który usuwamy są powiązane to tutaj dodajemy to powiązanie np.
        //$joins['country1'] = new QueryJoin(Country::class, 'country1', ['countryId' => 'country1.id']);

        return $joins;
    }
}
