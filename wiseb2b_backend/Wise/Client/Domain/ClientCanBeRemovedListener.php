<?php

declare(strict_types=1);

namespace Wise\Client\Domain;

use Wise\Client\Domain\Client\Events\ClientBeforeRemoveEvent;
use Wise\Client\Repository\Doctrine\ClientDeliveryMethodRepository;
use Wise\Client\Repository\Doctrine\ClientDocumentRepository;
use Wise\Client\Repository\Doctrine\ClientPaymentMethodRepository;
use Wise\Client\Repository\Doctrine\ClientPaymentRepository;
use Wise\Core\Exception\ValidationException;

class ClientCanBeRemovedListener
{
    public function __construct(
        protected ClientDeliveryMethodRepository $clientDeliveryMethodRepository,
        protected ClientPaymentMethodRepository $clientPaymentMethodRepository,
        protected ClientDocumentRepository $clientDocumentRepository,
        protected ClientPaymentRepository $clientPaymentRepository,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(ClientBeforeRemoveEvent $event): void
    {
        //if ($entity = $this->clientDeliveryMethodRepository->findOneBy(['clientId' => $event->getId()])) {
        //    $message = "Client cannot be removed because is used on Client Delivery Method: " . $entity->getId();
        //    throw (new ValidationException($message));
        //}

        if ($entity = $this->clientDocumentRepository->findOneBy(['clientId' => $event->getId()])) {
            $message = "Client cannot be removed because is used on Client Document: " . $entity->getId();
            throw (new ValidationException($message));
        }

        if ($entity = $this->clientPaymentRepository->findOneBy(['clientId' => $event->getId()])) {
            $message = "Client cannot be removed because is used on Client Payment: " . $entity->getId();
            throw (new ValidationException($message));
        }

        //if ($entity = $this->clientPaymentMethodRepository->findOneBy(['clientId' => $event->getId()    ])) {
        //    $message = "Client cannot be removed because is used on Client Payment Method: " . $entity->getId();
        //    throw (new ValidationException($message));
        //}
    }
}
