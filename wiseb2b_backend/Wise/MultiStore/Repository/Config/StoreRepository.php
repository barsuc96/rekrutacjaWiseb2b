<?php

declare(strict_types=1);

namespace Wise\MultiStore\Repository\Config;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Wise\Core\Repository\AbstractConfigRepository;
use Wise\MultiStore\Domain\Store\Service\Interfaces\StoreConfigurationHelperInterface;
use Wise\MultiStore\Domain\Store\Store;
use Wise\MultiStore\Domain\Store\StoreRepositoryInterface;
use Wise\MultiStore\WiseMultiStoreExtension;

class StoreRepository extends AbstractConfigRepository implements StoreRepositoryInterface
{
    protected const ENTITY_CLASS = Store::class;
    protected const EXTENSION = WiseMultiStoreExtension::class;

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly ContainerBagInterface $configParams,
        ManagerRegistry $registry,
        private readonly StoreConfigurationHelperInterface $storeConfigurationHelper,
        ?string $entity = null
    ){
        parent::__construct($this->kernel, $this->configParams, $registry, $entity);
    }

    protected function prepareData(): array
    {
        $data = $this->configParams->get(WiseMultiStoreExtension::ALIAS);
        $data = $data['stores'];

        $result = [];

        foreach ($data as $storeData){
            $result[] = [
                'symbol' => $storeData['symbol'],
                'id' => $storeData['id'],
                'name' => $storeData['name'],
            ];
        }

        return $result;
    }
}
