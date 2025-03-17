<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientDeliveryMethod;

use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethod;
use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethodRepositoryInterface;
use Wise\Client\Service\ClientDeliveryMethod\Interfaces\ClientDeliveryMethodHelperInterface;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Delivery\Domain\DeliveryMethod\DeliveryMethod;
use Wise\Delivery\Domain\DeliveryMethod\DeliveryMethodRepositoryInterface;

class ClientDeliveryMethodHelper implements ClientDeliveryMethodHelperInterface
{
    public function __construct(
        private readonly ClientDeliveryMethodRepositoryInterface $repository,
        private readonly DeliveryMethodRepositoryInterface $deliveryMethodRepository,
    ) {}

    public function findClientPaymentMethodForModify(array $data): ?ClientDeliveryMethod
    {
        $clientDeliveryMethod = null;
        $id = $data['id'] ?? null;
        $clientId = $data['clientId'] ?? null;
        $deliveryMethodExternalId = $data['deliveryMethodExternalId'] ?? null;

        if (null !== $id) {
            $clientDeliveryMethod = $this->repository->findOneBy(['id' => $id]);
            if (false === $clientDeliveryMethod instanceof ClientDeliveryMethod) {
                throw new ObjectNotFoundException(
                    'Nie znaleziono ClientDeliveryMethod o id: ' . $id
                );
            }
        }

        if (!$clientDeliveryMethod && $clientId && $deliveryMethodExternalId) {
            $deliveryMethod = $this->getDeliveryMethod([
                'deliveryMethodExternalId' => $deliveryMethodExternalId,
                'deliveryMethodId' => null
            ]);

            if (false === $deliveryMethod instanceof DeliveryMethod) {
                throw new ObjectNotFoundException(
                    'Nie znaleziono DeliveryMethod o externalId: ' . $deliveryMethodExternalId
                );
            }

            $clientDeliveryMethod = $this->repository->findOneBy(
                [
                    'clientId' => $clientId,
                    'deliveryMethodId' => $deliveryMethod->getId()
                ]
            );
        }

        return $clientDeliveryMethod;
    }

    public function getDeliveryMethod(array $data): DeliveryMethod
    {
        $deliveryMethodId = $data['deliveryMethodId'];
        $deliveryMethodExternalId = $data['deliveryMethodExternalId'];
        $deliveryMethod = null;

        if (null !== $deliveryMethodId) {
            $deliveryMethod = $this->deliveryMethodRepository->findOneBy(['id' => $deliveryMethodId]);
        } elseif (null !== $deliveryMethodExternalId) {
            $deliveryMethod =
                $this->deliveryMethodRepository->findOneBy(['idExternal' => $deliveryMethodExternalId]
                );
        }

        if (false === $deliveryMethod instanceof DeliveryMethod) {
            throw new ObjectNotFoundException(
                sprintf(
                    'Obiekt DeliveryMethod nie istnieje. Id: %s, externalId: %s',
                    $deliveryMethodId,
                    $deliveryMethodExternalId
                )
            );
        }

        return $deliveryMethod;
    }
}
