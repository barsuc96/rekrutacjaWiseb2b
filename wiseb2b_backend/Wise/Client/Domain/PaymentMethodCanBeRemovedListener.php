<?php

declare(strict_types=1);

namespace Wise\Client\Domain;

use Wise\Client\Repository\Doctrine\ClientPaymentMethodRepository;
use Wise\Client\Repository\Doctrine\ClientPaymentRepository;
use Wise\Core\Exception\ValidationException;
use Wise\Payment\Domain\PaymentMethod\PaymentMethodBeforeRemoveEvent;

class PaymentMethodCanBeRemovedListener
{
    public function __construct(
        protected ClientPaymentRepository $clientPaymentRepository,
        protected ClientPaymentMethodRepository $clientPaymentMethodRepository,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(PaymentMethodBeforeRemoveEvent $event): void
    {
        if ($entity = $this->clientPaymentRepository->findOneBy(['paymentMethodId' => $event->getId()])) {
            $message = "Payment Method cannot be removed because is used on Client Payment: " . $entity->getId();
            throw (new ValidationException($message));
        }

        if ($entity = $this->clientPaymentMethodRepository->findOneBy(['paymentMethodId' => $event->getId()])) {
            $message = "Payment Method cannot be removed because is used on Client Payment Method: " . $entity->getId();
            throw (new ValidationException($message));
        }
    }
}
