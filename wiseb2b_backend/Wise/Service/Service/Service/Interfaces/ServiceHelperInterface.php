<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service\Interfaces;

use Wise\Core\Service\Interfaces\CommonHelperInterface;
use Wise\Service\Domain\Service\Service;

interface ServiceHelperInterface extends CommonHelperInterface
{
    public function getServiceIdIfExists(?int $id = null, ?string $idExternal = null): ?int;

    /**
     * Wyszukuje usługę po id lub idExternal
     *
     * @param integer|null $id
     * @param string|null $idExternal
     * @return Service|null
     */
    public function getService(?int $id = null, ?string $idExternal = null): ?Service;

    /**
     * Zwraca domyślny driver z konfiguracji
     *
     * @return string|null
     */
    public function getDefaultDriverName(): ?string;

    /**
     * Zwraca nazwę drivera dla usługi
     *
     * @param integer $serviceId
     * @return string|null
     */
    public function getDriverNameByServiceId(int $serviceId): ?string;

    /**
     * Z podanej nazwy szukanego drivera i podanej listy dostępnych prowideróœ
     * zwraca prowidera pasującego do nazwy lub domyślny prowider
     *
     * @param string $driverName
     * @param array $providers
     * @return object|null
     */
    public function getProviderFromProviders(string $driverName, $providers): ?object;

    /**
     * Zwraca role usługi
     * @param int $id
     * @return string|null
     */
    public function getServiceRole(int $id): ?string;
}
