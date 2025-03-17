<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\Agreements;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\User\ApiAdmin\Dto\Agreements\DeleteAgreementsByKeyAttributesDto;
use Wise\User\ApiAdmin\Service\Agreements\Interfaces\DeleteAgreementsByKeyServiceInterface;
use Wise\User\Service\Agreement\Interfaces\RemoveAgreementServiceInterface;

class DeleteAgreementsByKeyService extends AbstractDeleteService implements DeleteAgreementsByKeyServiceInterface
{
    #[Pure]
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly RemoveAgreementServiceInterface $service
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @throws ReflectionException
     * @throws InvalidInputArgumentException
     */
    public function delete(AbstractDto $deleteDto): array
    {
        if (!($deleteDto instanceof DeleteAgreementsByKeyAttributesDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane w atrybutach ścieżki: '.$deleteDto::class);
        }

        // BARDZO WAŻNE, Walidacja techniczna parametrów wejściowych
        if ($deleteDto->isInitialized('agreementId') === false) {
            throw new InvalidInputArgumentException(
                'Do usunięcia agreement, należy podać id zewnętrzne agreement do usunięcia'
            );
        }

        $fields =
            [
                'agreementId' => 'idExternal',
            ];

        $deleteDtoData = CommonDataTransformer::transformToArray($deleteDto, $fields);

        ($serviceDTO = new CommonServiceDTO())->writeAssociativeArray($deleteDtoData);

        $serviceDTO = ($this->service)($serviceDTO);

        return $serviceDTO->read();
    }
}
