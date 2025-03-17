<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Service\Countries;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\I18n\ApiAdmin\Dto\Countries\PutCountryDto;
use Wise\I18n\ApiAdmin\Service\Countries\Interfaces\PutCountriesServiceInterface;
use Wise\I18n\Service\Country\Interfaces\AddOrModifyCountryServiceInterface;

class PutCountriesService extends AbstractPutService implements PutCountriesServiceInterface
{
    #[Pure]
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyCountryServiceInterface $service,
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @param PutCountryDto $putDto
     *
     * @return CommonObjectIdResponseDto
     */
    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto
    {
        if (false === $putDto instanceof PutCountryDto) {
            throw new InvalidArgumentException(
                'Niepoprawne DTO otrzymane na wejście requesta: ' . $putDto::class
            );
        }

        ($serviceDTO = new CommonModifyParams())->write($putDto, [
            'id' => 'idExternal',
            'internalId' => 'id',
        ]);

        $serviceDTO->setMergeNestedObjects($isPatch);
        $serviceDTO = ($this->service)($serviceDTO, $isPatch);

        $this->adminApiShareMethodsHelper->repositoryManager->flush();

        return (new CommonObjectIdResponseDto())
            ->setInternalId($serviceDTO->read()['id'] ?? null)
            ->setId($serviceDTO->read()['idExternal'] ?? null);
    }
}
