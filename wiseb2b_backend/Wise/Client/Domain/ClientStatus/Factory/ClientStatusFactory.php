<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientStatus\Factory;

use Wise\Client\Domain\ClientStatus\ClientStatus;

class ClientStatusFactory
{
    public function __construct(
        private readonly string $entity,
    ) {}

    /**
     * Tworzy nowy obiekt OrderStatus
     * @param array $config
     * @return ClientStatus
     */
    public function create(array $config): ClientStatus
    {
        if(!isset($config['id']) || !isset($config['symbol'])) {
            throw new \InvalidArgumentException('Status or status_number is not set');
        }

        /** @var ClientStatus $entity */
        $entity = new ($this->entity)();
        $entity->setSymbol($config['symbol']);
        $entity->setId($config['id']);
        return $entity;
    }
}
