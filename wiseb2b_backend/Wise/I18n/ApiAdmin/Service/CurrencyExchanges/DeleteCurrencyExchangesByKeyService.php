<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Service\CurrencyExchanges;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\I18n\ApiAdmin\Dto\CurrencyExchanges\DeleteCurrencyExchangesByKeyAttributesDto;
use Wise\I18n\ApiAdmin\Service\CurrencyExchanges\Interfaces\DeleteCurrencyExchangesByKeyServiceInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\RemoveCurrencyExchangeServiceInterface;

class DeleteCurrencyExchangesByKeyService extends AbstractDeleteService implements DeleteCurrencyExchangesByKeyServiceInterface
{
    #[Pure]
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly RemoveCurrencyExchangeServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @throws ReflectionException
     * @throws InvalidInputArgumentException
     */
    public function delete(AbstractDto $deleteDto): array
    {
        if (!($deleteDto instanceof DeleteCurrencyExchangesByKeyAttributesDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane w atrybutach ścieżki: '.$deleteDto::class);
        }

        // BARDZO WAŻNE, Walidacja techniczna parametrów wejściowych
        if ($deleteDto->isInitialized('currencyExchangeId') === false) {
            throw new InvalidInputArgumentException(
                'Do usunięcia currencyExchange, należy podać id zewnętrzne currencyExchange do usunięcia'
            );
        }

        $fields =
            [
                'currencyExchangeId' => 'idExternal',
            ];

        $deleteDtoData = CommonDataTransformer::transformToArray($deleteDto, $fields);

        ($serviceDTO = new CommonServiceDTO())->writeAssociativeArray($deleteDtoData);

        $serviceDTO = ($this->service)($serviceDTO);

        return $serviceDTO->read();
    }
}
