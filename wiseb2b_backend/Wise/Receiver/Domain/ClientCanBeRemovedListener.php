<?php

declare(strict_types=1);

namespace Wise\Receiver\Domain;

use Wise\Client\Domain\Client\Events\ClientBeforeRemoveEvent;
use Wise\Core\Exception\ValidationException;
use Wise\Receiver\Repository\Doctrine\ReceiverRepository;

class ClientCanBeRemovedListener
{
    public function __construct(
        protected ReceiverRepository $repository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(ClientBeforeRemoveEvent $event): void
    {
        if ($entity = $this->repository->findOneBy(['clientId' => $event->getId(), 'isActive' => true])) {
            $message = "Client cannot be removed because is used on Receiver: " . $entity->getId();
            throw (new ValidationException($message));
        }
    }
}
