<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiAdmin\Service\Receivers;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Receiver\ApiAdmin\Dto\Receivers\PutReceiverDto;
use Wise\Receiver\ApiAdmin\Service\Receivers\Interfaces\PutReceiversServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\AddOrModifyReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverHelperInterface;

class PutReceiversService extends AbstractPutService implements PutReceiversServiceInterface
{
    #[Pure]
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyReceiverServiceInterface $service,
        private readonly ReceiverHelperInterface $receiverHelper,
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @param PutReceiverDto $putDto
     * @return CommonObjectIdResponseDto
     */
    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto
    {
        if (!($putDto instanceof PutReceiverDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane na wejście requesta: '.$putDto::class);
        }

        if($putDto->isInitialized('deliveryAddress') && $putDto->getDeliveryAddress() !== null){
            $this->receiverHelper->validateCountryCode($putDto?->getDeliveryAddress()?->getCountryCode());
        }

        ($serviceDTO = new CommonModifyParams())->write($putDto, [
            'id' => 'idExternal',
            'internalId' => 'id',
            'clientId' => 'clientIdExternal'
        ]);

        $serviceDTO->setMergeNestedObjects($isPatch);
        $serviceDTO = ($this->service)($serviceDTO);

        $this->adminApiShareMethodsHelper->repositoryManager->flush();
        $commonObjectIdResponseDto = new CommonObjectIdResponseDto();

        return $commonObjectIdResponseDto
            ->setInternalId($serviceDTO->read()['id'] ?? null)
            ->setId($serviceDTO->read()['idExternal'] ?? null);
    }
}
