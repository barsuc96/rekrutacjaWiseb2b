<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\UserAgreements;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\User\ApiAdmin\Dto\UserAgreements\DeleteUserAgreementsByKeyAttributesDto;
use Wise\User\ApiAdmin\Service\UserAgreements\Interfaces\DeleteUserAgreementsByKeyServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\RemoveUserAgreementServiceInterface;

class DeleteUserAgreementsByKeyService extends AbstractDeleteService implements DeleteUserAgreementsByKeyServiceInterface
{
    #[Pure]
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly RemoveUserAgreementServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @throws ReflectionException
     * @throws InvalidInputArgumentException
     */
    public function delete(AbstractDto $deleteDto): array
    {
        if (!($deleteDto instanceof DeleteUserAgreementsByKeyAttributesDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane w atrybutach ścieżki: '.$deleteDto::class);
        }

        // BARDZO WAŻNE, Walidacja techniczna parametrów wejściowych
        if ($deleteDto->isInitialized('userAgreementId') === false) {
            throw new InvalidInputArgumentException(
                'Do usunięcia userAgreement, należy podać id zewnętrzne userAgreement do usunięcia'
            );
        }

        $fields =
            [
                'userAgreementId' => 'idExternal',
            ];

        $deleteDtoData = CommonDataTransformer::transformToArray($deleteDto, $fields);

        ($serviceDTO = new CommonServiceDTO())->writeAssociativeArray($deleteDtoData);

        $serviceDTO = ($this->service)($serviceDTO);

        return $serviceDTO->read();
    }
}
