<?php

declare(strict_types=1);

namespace Wise\Client\Domain;

use Wise\Client\Repository\Doctrine\ClientDeliveryMethodRepository;
use Wise\Core\Exception\ValidationException;
use Wise\Delivery\Domain\DeliveryMethod\DeliveryMethodBeforeRemoveEvent;

class DeliveryMethodCanBeRemovedListener
{
    public function __construct(
        protected ClientDeliveryMethodRepository $repository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(DeliveryMethodBeforeRemoveEvent $event): void
    {
        if ($entity = $this->repository->findOneBy(['deliveryMethodId' => $event->getId()])) {
            $message = "Delivery Method cannot be removed because is used on Client Delivery Method: " . $entity->getId();
            throw (new ValidationException($message));
        }
    }
}
