<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractHelper;
use Wise\Service\Domain\Service\Service;
use Wise\Service\Domain\Service\ServiceRepositoryInterface;
use Wise\Service\Domain\Service\ServicesServiceInterface;
use Wise\Service\Service\Service\Interfaces\ListByFiltersServiceServiceInterface;
use Wise\Service\Service\Service\Interfaces\ServiceHelperInterface;
use Wise\Service\WiseServiceExtension;

class ServiceHelper extends AbstractHelper implements ServiceHelperInterface
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
        private readonly ListByFiltersServiceServiceInterface $listByFiltersService,
        private readonly ContainerBagInterface $configParams,
        private readonly ServicesServiceInterface $servicesService,
    ){
        parent::__construct(
            entityDomainService: $servicesService
        );
    }

    public function getServiceIdIfExists(?int $id = null, ?string $idExternal = null): ?int
    {
        $serviceId = false;

        if (null !== $id) {
            $serviceId = current(
                $this->serviceRepository->findByQueryFiltersView(
                    queryFilters: [(new QueryFilter('id', $id))],
                    fields: ['id']
                )
            );
        } elseif (null !== $idExternal) {
            $serviceId = current(
                $this->serviceRepository->findByQueryFiltersView(
                    queryFilters: [(new QueryFilter('idExternal', $idExternal))],
                    fields: ['id']
                )
            );
        }

        if ($serviceId === false || !isset($serviceId['id'])) {
            throw new ObjectNotFoundException(
                sprintf('Obiekt Service nie istnieje. Id: %s, IdExternal: %s', $id, $idExternal)
            );
        }

        return $serviceId['id'];
    }

    public function getService(?int $id = null, ?string $idExternal = null): ?Service
    {
        $service = null;

        if (null !== $id) {
            $service = $this->serviceRepository->findOneBy(['id' => $id]);
        } elseif (null !== $idExternal) {
            $service = $this->serviceRepository->findOneBy(['idExternal' => $idExternal]);
        }

        return $service;
    }

    /**
     * Zwraca nazwę drivera obsługującego usługę
     * @param int $serviceId
     * @return string|null
     * @throws ObjectNotFoundException
     */
    public function getDriverNameByServiceId(int $serviceId): ?string
    {
        $service = $this->getService($serviceId);

        if($service === null){
            throw new ObjectNotFoundException("Nie istnieje usługa o id: {$serviceId}");
        }

        return $service->getDriverName();
    }


    /**
     * Zwraca nazwę defaultowego drivera
     * @return string|null
     */
    public function getDefaultDriverName(): ?string
    {
        $config = $this->configParams->get(WiseServiceExtension::getExtensionAlias());
        return $config['service_driver']['default_driver_name'] ?? null;
    }

    public function getProviderFromProviders($providerName, $providers): ?object
    {
        $foundProviderService = null;

        // 1. Szukamy metody providera
        if(!empty($providerName)){
            foreach ($providers as $provider) {
                if ($provider->supports($providerName)) {
                    $foundProviderService = $provider;
                }
            }
        }

        // 2. Jeśli nie znaleziono metody providera szukamy defaultowego
        if($foundProviderService === null){
            $providerName = $this->getDefaultDriverName();
            foreach ($providers as $provider) {
                if ($provider->supports($providerName)) {
                    $foundProviderService = $provider;
                }
            }
        }

        return $foundProviderService;
    }

    /**
     * Zwraca role usługi
     * @param int $id
     * @return string|null
     */
    public function getServiceRole(int $id): ?string
    {
        /** @var Service $service */
        $service = $this->serviceRepository->find($id);

        if($service === null){
            return null;
        }

        return match ($service->getDriverName()) {
            'payment_standard' => 'payment',
            'delivery_standard' => 'delivery',
            default => null,
        };
    }

    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param array $data
     * @param bool $executeNotFoundException
     * @return int|null
     * @throws \ReflectionException
     */
    public function getIdIfExistByDataExternal(array $data, bool $executeNotFoundException = true): ?int
    {
        $id = $data['serviceId'] ?? null;
        $idExternal = $data['serviceIdExternal'] ?? null;

        return $this->servicesService->getIdIfExist($id, $idExternal, $executeNotFoundException);
    }

    /**
     * Zwraca identyfikator encji na podstawie date, jeśli znajdują się tam zewnętrzne klucze
     * @param array $data
     * @param bool $executeNotFoundException
     * @return void
     */
    public function prepareExternalData(array &$data, bool $executeNotFoundException = true): void
    {
        // Sprawdzam, czy istnieją pola
        if(!isset($data['serviceId']) && !isset($data['serviceIdExternal']) && !isset($data['serviceExternalId'])){
            return;
        }

        // Pobieram identyfikator
        $id = $data['serviceId'] ?? null;
        $idExternal = $data['serviceIdExternal'] ?? $data['serviceExternalId'] ?? null;

        $data['serviceId'] = $this->servicesService->getIdIfExist($id, $idExternal, $executeNotFoundException);

        // Usuwam pola zewnętrzne
        unset($data['serviceIdExternal']);
        unset($data['serviceExternalId']);
    }
}
