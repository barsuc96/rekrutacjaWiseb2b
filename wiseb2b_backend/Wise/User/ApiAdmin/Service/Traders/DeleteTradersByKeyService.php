<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\Traders;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\User\ApiAdmin\Dto\Traders\DeleteTradersByKeyAttributesDto;
use Wise\User\ApiAdmin\Service\Traders\Interfaces\DeleteTradersByKeyServiceInterface;
use Wise\User\Service\Trader\Interfaces\RemoveTraderServiceInterface;

class DeleteTradersByKeyService extends AbstractDeleteService implements DeleteTradersByKeyServiceInterface
{
    #[Pure]
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly RemoveTraderServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @throws ReflectionException
     * @throws InvalidInputArgumentException
     */
    public function delete(AbstractDto $deleteDto): array
    {
        if (!($deleteDto instanceof DeleteTradersByKeyAttributesDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane w atrybutach ścieżki: '.$deleteDto::class);
        }

        // BARDZO WAŻNE, Walidacja techniczna parametrów wejściowych
        if ($deleteDto->isInitialized('traderId') === false) {
            throw new InvalidInputArgumentException(
                'Do usunięcia trader, należy podać id zewnętrzne trader do usunięcia'
            );
        }

        $fields =
            [
                'traderId' => 'idExternal',
            ];

        $deleteDtoData = CommonDataTransformer::transformToArray($deleteDto, $fields);

        ($serviceDTO = new CommonServiceDTO())->writeAssociativeArray($deleteDtoData);

        $serviceDTO = ($this->service)($serviceDTO);

        return $serviceDTO->read();
    }
}
