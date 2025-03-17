<?php

declare(strict_types=1);

namespace Wise\User\Domain;

use Wise\Core\Exception\ValidationException;
use Wise\User\Domain\Trader\TraderBeforeRemoveEvent;
use Wise\User\Repository\Doctrine\UserRepository;

class TraderCanBeRemovedListener
{
    public function __construct(
        protected UserRepository $repository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(TraderBeforeRemoveEvent $event): void
    {
        if ($entity = $this->repository->findOneBy(['traderId' => $event->getId()])) {
            $message = "Trader cannot be removed because is used on User: " . $entity->getId();
            throw (new ValidationException($message));
        }
    }
}
