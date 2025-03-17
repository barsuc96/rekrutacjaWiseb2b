<?php

declare(strict_types=1);

namespace Wise\User\Domain;

use Wise\Core\Exception\ValidationException;
use Wise\User\Domain\Agreement\AgreementBeforeRemoveEvent;
use Wise\User\Repository\Doctrine\UserAgreementRepository;

class AgreementCanBeRemovedListener
{
    public function __construct(
        protected UserAgreementRepository $repository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(AgreementBeforeRemoveEvent $event): void
    {
        if ($entity = $this->repository->findOneBy(['agreementId' => $event->getId()])) {
            $message = "Agreement cannot be removed because is used on User Agreement: " . $entity->getId();
            throw (new ValidationException($message));
        }
    }
}
