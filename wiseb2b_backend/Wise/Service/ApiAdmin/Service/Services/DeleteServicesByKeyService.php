<?php

declare(strict_types=1);

namespace Wise\Service\ApiAdmin\Service\Services;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonRemoveParams;
use Wise\Service\ApiAdmin\Dto\Services\DeleteServicesByKeyAttributesDto;
use Wise\Service\ApiAdmin\Service\Services\Interfaces\DeleteServicesByKeyServiceInterface;
use Wise\Service\Service\Service\Interfaces\RemoveServiceServiceInterface;

class DeleteServicesByKeyService extends AbstractDeleteService implements DeleteServicesByKeyServiceInterface
{
    #[Pure]
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly RemoveServiceServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @throws ReflectionException
     * @throws InvalidInputArgumentException
     */
    public function delete(AbstractDto $deleteDto): array
    {
        if (!($deleteDto instanceof DeleteServicesByKeyAttributesDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane w atrybutach ścieżki: '.$deleteDto::class);
        }

        // BARDZO WAŻNE, Walidacja techniczna parametrów wejściowych
        if ($deleteDto->isInitialized('serviceId') === false) {
            throw new InvalidInputArgumentException(
                'Do usunięcia service, należy podać id zewnętrzne service do usunięcia'
            );
        }

        $fields =
            [
                'serviceId' => 'idExternal',
            ];

        $serviceDTO = new CommonRemoveParams();
        $filters = [];
        foreach (CommonDataTransformer::transformToArray($deleteDto, $fields) as $field => $value) {
            $filters[] = new QueryFilter($field, $value);
        }

        $serviceDTO
            ->setFilters($filters)
            ->setContinueAfterError(false);

        $serviceDTO = ($this->service)($serviceDTO);

        return $serviceDTO->read();
    }
}
