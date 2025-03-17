<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Service\ClientPaymentMethods;

use Symfony\Component\Serializer\SerializerInterface;
use Wise\Client\ApiAdmin\Dto\ClientPaymentMethods\PutClientPaymentMethodDto;
use Wise\Client\ApiAdmin\Service\ClientPaymentMethods\Interfaces\PutClientPaymentMethodsServiceInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\AddOrModifyClientPaymentMethodServiceInterface;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\ApiAdmin\ServiceInterface\RequestUuidServiceInterface;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\Core\Service\Admin\ReplicationService;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\Interfaces\Admin\ReplicationServiceInterface;
use Wise\Core\Validator\ObjectValidator;

class PutClientPaymentMethodsService extends AbstractPutService implements
    PutClientPaymentMethodsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyClientPaymentMethodServiceInterface $service,
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @param PutClientPaymentMethodDto $putDto
     *
     * @return CommonObjectIdResponseDto
     */
    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto
    {
        if (!($putDto instanceof PutClientPaymentMethodDto)) {
            throw new \InvalidArgumentException(
                'Niepoprawne DTO otrzymane na wejście requesta: ' . $putDto::class
            );
        }

        ($serviceDTO = new CommonServiceDTO())->write($putDto, [
            'internalId' => 'id',
            'clientId' => 'clientExternalId',
            'clientInternalId' => 'clientId',
            'paymentMethodId' => 'paymentMethodExternalId',
            'paymentMethodInternalId' => 'paymentMethodId',
        ]);

        $serviceDTO = ($this->service)($serviceDTO);

        $this->adminApiShareMethodsHelper->repositoryManager->flush();

        $response = (new CommonObjectIdResponseDto());
        $response->prepareFromData($putDto);
        $response
            ->setInternalId($serviceDTO->read()['id'] ?? null);

        return $response;
    }
}
