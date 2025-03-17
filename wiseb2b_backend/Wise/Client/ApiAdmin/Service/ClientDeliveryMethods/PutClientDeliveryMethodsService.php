<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Service\ClientDeliveryMethods;

use Wise\Client\ApiAdmin\Dto\ClientDeliveryMethods\PutClientDeliveryMethodDto;
use Wise\Client\ApiAdmin\Service\ClientDeliveryMethods\Interfaces\PutClientDeliveryMethodsServiceInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\AddOrModifyClientDeliveryMethodServiceInterface;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;

class PutClientDeliveryMethodsService extends AbstractPutService implements PutClientDeliveryMethodsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyClientDeliveryMethodServiceInterface $service,
    ) {
        parent::__construct(
            $adminApiShareMethodsHelper
        );
    }

    /**
     * @param PutClientDeliveryMethodDto $putDto
     * @return CommonObjectIdResponseDto
     */
    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto
    {
        if (!($putDto instanceof PutClientDeliveryMethodDto)) {
            throw new \InvalidArgumentException(
                'Niepoprawne DTO otrzymane na wejście requesta: ' . $putDto::class
            );
        }

        ($serviceDTO = new CommonServiceDTO())->write($putDto, [
            'internalId' => 'id',
            'clientId' => 'clientExternalId',
            'clientInternalId' => 'clientId',
            'deliveryMethodId' => 'deliveryMethodExternalId',
            'deliveryMethodInternalId' => 'deliveryMethodId',
        ]);

        $serviceDTO = ($this->service)($serviceDTO);

        $this->adminApiShareMethodsHelper->repositoryManager->flush();

        $response = (new CommonObjectIdResponseDto());
        $response->prepareFromData($putDto);
        $response
            ->setInternalId($serviceDTO->read()['id'] ?? null)
            ->setId($serviceDTO->read()['idExternal'] ?? null);

        return $response;
    }
}
