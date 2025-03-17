<?php

declare(strict_types=1);

namespace Wise\Client\Domain;

use Wise\Client\Repository\Doctrine\ClientRepository;
use Wise\Core\Exception\ValidationException;
use Wise\User\Domain\Trader\TraderBeforeRemoveEvent;

class TraderCanBeRemovedListener
{
    public function __construct(
        protected ClientRepository $repository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(TraderBeforeRemoveEvent $event): void
    {
        if ($entity = $this->repository->findOneBy(['traderId' => $event->getId()])) {
            $message = "Trader cannot be removed because is used on Client: " . $entity->getId();
            throw (new ValidationException($message));
        }
    }
}
