<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientPaymentMethod;

use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethod;
use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethodRepositoryInterface;
use Wise\Client\Service\ClientPaymentMethod\Interfaces\ClientPaymentMethodHelperInterface;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Payment\Domain\PaymentMethod\PaymentMethod;
use Wise\Payment\Domain\PaymentMethod\PaymentMethodRepositoryInterface;

class ClientPaymentMethodHelper implements ClientPaymentMethodHelperInterface
{
    public function __construct(
        private readonly ClientPaymentMethodRepositoryInterface $repository,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository,
    ) {}

    public function findClientPaymentMethodForModify(array $data): ?ClientPaymentMethod
    {
        $clientPaymentMethod = null;
        $id = $data['id'] ?? null;
        $clientId = $data['clientId'] ?? null;
        $paymentMethodExternalId = $data['paymentMethodExternalId'] ?? null;


        if (null !== $id) {
            $clientPaymentMethod = $this->repository->findOneBy(['id' => $id]);
            if (false === $clientPaymentMethod instanceof ClientPaymentMethod) {
                throw new ObjectNotFoundException(
                    'Nie znaleziono ClientPaymentMethod o id: ' . $id
                );
            }
        }

        if (!$clientPaymentMethod && $clientId && $paymentMethodExternalId) {
            $paymentMethod = $this->getPaymentMethod([
                'paymentMethodExternalId' => $paymentMethodExternalId,
                'paymentMethodId' => null
            ]);
            if (false === $paymentMethod instanceof PaymentMethod) {
                throw new ObjectNotFoundException(
                    'Nie znaleziono PaymentMethod o externalId: ' . $paymentMethodExternalId
                );
            }
            $clientPaymentMethod = $this->repository->findOneBy(
                [
                    'clientId' => $clientId,
                    'paymentMethodId' => $paymentMethod->getId()
                ]
            );
        }

        return $clientPaymentMethod;
    }

    public function getPaymentMethod(array $data): PaymentMethod
    {
        $paymentMethodId = $data['paymentMethodId'];
        $paymentMethodExternalId = $data['paymentMethodExternalId'];
        $paymentMethod = null;

        if (null !== $paymentMethodId) {
            $paymentMethod = $this->paymentMethodRepository->findOneBy(['id' => $paymentMethodId]);
        } elseif (null !== $paymentMethodExternalId) {
            $paymentMethod =
                $this->paymentMethodRepository->findOneBy(['idExternal' => $paymentMethodExternalId]
                );
        }

        if (false === $paymentMethod instanceof PaymentMethod) {
            throw new ObjectNotFoundException(
                sprintf(
                    'Obiekt PaymentMethod nie istnieje. Id: %s, externalId: %s',
                    $paymentMethodId,
                    $paymentMethodExternalId
                )
            );
        }

        return $paymentMethod;
    }
}
