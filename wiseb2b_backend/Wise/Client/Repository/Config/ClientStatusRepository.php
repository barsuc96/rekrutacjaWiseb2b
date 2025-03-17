<?php

declare(strict_types=1);

namespace Wise\Client\Repository\Config;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Wise\Client\Domain\ClientStatus\ClientStatus;
use Wise\Client\Domain\ClientStatus\ClientStatusRepositoryInterface;
use Wise\Client\WiseClientExtension;
use Wise\Core\Repository\AbstractConfigRepository;

class ClientStatusRepository extends AbstractConfigRepository implements ClientStatusRepositoryInterface
{
    protected const ENTITY_CLASS = ClientStatus::class;
    protected const EXTENSION = WiseClientExtension::class;

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly ContainerBagInterface $configParams,
        ManagerRegistry $registry,
        ?string $entity = null
    ){
        parent::__construct($this->kernel, $this->configParams, $registry, $entity);
    }

    protected function prepareData(): array
    {
        $data = $this->getConfigData()['client_status'];
        $result = [];

        foreach ($data as $status => $statusData){
            $result[] = [
                'symbol' => $status,
                'id' => $statusData['status_number']
            ];
        }

        return $result;
    }
}
