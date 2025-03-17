<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client\Factory;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\Events\ClientHasCreatedEvent;
use Wise\Client\WiseClientExtension;
use Wise\Core\Domain\AbstractEntityFactory;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\Core\Service\Merge\MergeService;

class ClientFactory extends AbstractEntityFactory
{
    protected const HAS_CREATED_EVENT_NAME = ClientHasCreatedEvent::class;

    public function __construct(
        private readonly string $entity,
        private readonly MergeService $mergeService,
        private readonly ContainerBagInterface $configParams,
        private readonly ConfigServiceInterface $configService
    ){
        parent::__construct($entity, $mergeService);
    }

    /**
     * Umożliwia ustawienie domyślnych wartości dla encji podczas tworzenia encji.
     * @param Client|AbstractEntity $entity
     * @return void
     */
    protected function setDefaultValues(Client|AbstractEntity $entity): void
    {
        if($entity->getIsActive() === null) {
            $entity->setIsActive(true);
        }

        if($entity->getStatus() === null) {
            $entity->setStatus($this->loadDefaultClientStatus());
        }

        if($entity->getClientParentId() === null) {
            $entity->setClientParentId(0);
        }

        if($entity->getClientGroupId() === null) {
            $entity->setClientGroupId(1);
        }

        if(!$entity->isInitialized('defaultCurrency') || $entity->getDefaultCurrency() === null) {
            $entity->setDefaultCurrency($this->configService->get('default_currency'));
        }
    }

    /**
     * Zwraca domyślny status klienta z konfiguracji
     * @return int Domyślny status klienta
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function loadDefaultClientStatus(): int
    {
        $serviceConfig = $this->configService->get(WiseClientExtension::getExtensionAlias());

        // Statusy zamówień ustalane są w pliku konfiguracyjnym
        return intval($serviceConfig['default_client_status']);
    }
}
