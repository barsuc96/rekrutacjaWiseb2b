<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\Users;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\User\ApiAdmin\Dto\Users\DeleteUsersByKeyAttributesDto;
use Wise\User\ApiAdmin\Service\Users\Interfaces\DeleteUsersByKeyServiceInterface;
use Wise\User\Service\User\Interfaces\RemoveUserServiceInterface;

class DeleteUsersByKeyService extends AbstractDeleteService implements DeleteUsersByKeyServiceInterface
{
    #[Pure]
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly RemoveUserServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @throws ReflectionException
     * @throws InvalidInputArgumentException
     */
    public function delete(AbstractDto $deleteDto): array
    {
        if (!($deleteDto instanceof DeleteUsersByKeyAttributesDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane w atrybutach ścieżki: '.$deleteDto::class);
        }

        // BARDZO WAŻNE, Walidacja techniczna parametrów wejściowych
        if ($deleteDto->isInitialized('userId') === false) {
            throw new InvalidInputArgumentException(
                'Do usunięcia user, należy podać id zewnętrzne user do usunięcia'
            );
        }

        $fields =
            [
                'userId' => 'idExternal',
            ];

        $deleteDtoData = CommonDataTransformer::transformToArray($deleteDto, $fields);

        ($serviceDTO = new CommonServiceDTO())->writeAssociativeArray($deleteDtoData);

        $serviceDTO = ($this->service)($serviceDTO);

        return $serviceDTO->read();
    }
}
