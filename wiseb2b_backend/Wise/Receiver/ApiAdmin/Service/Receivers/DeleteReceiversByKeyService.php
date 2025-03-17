<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiAdmin\Service\Receivers;

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
use Wise\Receiver\ApiAdmin\Dto\Receivers\DeleteReceiversByKeyAttributesDto;
use Wise\Receiver\ApiAdmin\Service\Receivers\Interfaces\DeleteReceiversByKeyServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\RemoveReceiverServiceInterface;

class DeleteReceiversByKeyService extends AbstractDeleteService implements DeleteReceiversByKeyServiceInterface
{
    #[Pure]
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly RemoveReceiverServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @throws ReflectionException
     * @throws InvalidInputArgumentException
     */
    public function delete(AbstractDto $deleteDto): array
    {
        if (!($deleteDto instanceof DeleteReceiversByKeyAttributesDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane w atrybutach ścieżki: '.$deleteDto::class);
        }

        // BARDZO WAŻNE, Walidacja techniczna parametrów wejściowych
        if ($deleteDto->isInitialized('receiverId') === false) {
            throw new InvalidInputArgumentException(
                'Do usunięcia receiver, należy podać id zewnętrzne receiver do usunięcia'
            );
        }

        $fields =
            [
                'receiverId' => 'idExternal',
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
