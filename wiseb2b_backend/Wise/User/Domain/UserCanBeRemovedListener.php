<?php

declare(strict_types=1);

namespace Wise\User\Domain;

use Wise\Core\Exception\ValidationException;
use Wise\User\Domain\User\UserBeforeRemoveEvent;
use Wise\User\Repository\Doctrine\UserAgreementRepository;
use Wise\User\Repository\Doctrine\UserLoginHistoryRepository;
use Wise\User\Repository\Doctrine\UserRelationRepository;

class UserCanBeRemovedListener
{
    public function __construct(
        protected UserAgreementRepository $userAgreementRepository,
        protected UserLoginHistoryRepository $userLoginHistoryRepository,
        protected UserRelationRepository $userRelationRepository,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(UserBeforeRemoveEvent $event): void
    {
        if ($entity = $this->userAgreementRepository->findOneBy(['userId' => $event->getId()])) {
            $message = "User cannot be removed because is used on User Agreement: " . $entity->getId();
            throw (new ValidationException($message));
        }

        if ($entity = $this->userLoginHistoryRepository->findOneBy(['userId' => $event->getId()])) {
            $message = "User cannot be removed because is used on User Login History: " . $entity->getId();
            throw (new ValidationException($message));
        }

        if ($entity = $this->userRelationRepository->findOneBy(['userId' => $event->getId()])) {
            $message = "User cannot be removed because is used on User Relation: " . $entity->getId();
            throw (new ValidationException($message));
        }
    }
}
