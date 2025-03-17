<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Service\Countries;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\I18n\ApiAdmin\Dto\Countries\DeleteCountriesByKeyAttributesDto;
use Wise\I18n\ApiAdmin\Service\Countries\Interfaces\DeleteCountriesByKeyServiceInterface;
use Wise\I18n\Service\Country\Interfaces\RemoveCountryServiceInterface;

class DeleteCountriesByKeyService extends AbstractDeleteService implements DeleteCountriesByKeyServiceInterface
{
    #[Pure]
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly RemoveCountryServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @throws ReflectionException
     * @throws InvalidInputArgumentException
     */
    public function delete(AbstractDto $deleteDto): array
    {
        if (!($deleteDto instanceof DeleteCountriesByKeyAttributesDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane w atrybutach ścieżki: '.$deleteDto::class);
        }

        // BARDZO WAŻNE, Walidacja techniczna parametrów wejściowych
        if ($deleteDto->isInitialized('countryId') === false) {
            throw new InvalidInputArgumentException(
                'Do usunięcia country, należy podać id zewnętrzne country do usunięcia'
            );
        }

        $fields =
            [
                'countryId' => 'idExternal',
            ];

        $deleteDtoData = CommonDataTransformer::transformToArray($deleteDto, $fields);

        ($serviceDTO = new CommonServiceDTO())->writeAssociativeArray($deleteDtoData);

        $serviceDTO = ($this->service)($serviceDTO);

        return $serviceDTO->read();
    }
}
