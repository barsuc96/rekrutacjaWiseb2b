<?php

declare(strict_types=1);

namespace Wise\Client\Domain;

use Wise\Client\Repository\Doctrine\ClientRepository;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\ValidationException;
use Wise\Pricing\Domain\PriceList\PriceListBeforeRemoveEvent;

class PriceListCanBeRemovedListener
{
    public function __construct(
        protected ClientRepository $repository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(PriceListBeforeRemoveEvent $event): void
    {
        if ($entity = $this->repository->findOneBy(['pricelistId' => $event->getId()])) {
            $message = "Price List cannot be removed because is used on Client: " . $entity->getId();
            throw (new CommonLogicException())->setMessageException($message);
        }
    }
}
