<?php

declare(strict_types=1);

namespace Wise\User\Domain;

use Wise\Client\Domain\Client\Events\ClientBeforeRemoveEvent;
use Wise\Core\Exception\ValidationException;
use Wise\User\Repository\Doctrine\UserRepository;

class ClientCanBeRemovedListener
{
    public function __construct(
        protected UserRepository $repository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(ClientBeforeRemoveEvent $event): void
    {
        if ($entity = $this->repository->findOneBy(['clientId' => $event->getId(), 'isActive' => true])) {
            $message = "Client cannot be removed because is used on User: " . $entity->getId();
            throw (new ValidationException($message));
        }
    }
}
