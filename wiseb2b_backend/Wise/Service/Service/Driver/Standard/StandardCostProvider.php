<?php

namespace Wise\Service\Service\Driver\Standard;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Model\QueryFilter;
use Wise\Service\Domain\Service\ServiceCostCalcMethodEnum;
use Wise\Service\Domain\Service\ServiceCostCalculatorStandard;
use Wise\Service\Domain\ServiceCostProviderInterface;
use Wise\Service\Domain\ServiceCostInfo;
use Wise\Service\Service\Service\Interfaces\ListByFiltersServiceServiceInterface;

/**
 * Serwis do wyliczania kosztu usługi na podstawie danych z costCalcMethod i costCalcParam
 * uwzględniający koszt pozycji w koszyku
 */
class StandardCostProvider extends AbstractStandardProvider implements ServiceCostProviderInterface
{
    public function __construct(
        private readonly ListByFiltersServiceServiceInterface $listByFiltersService,
    )
    {
    }

    public function __invoke(int $serviceId, CommonServiceDTO $cartData): ServiceCostInfo
    {
        $service = $this->getService($cartData->read()['dedicatedServiceId']);

        $serviceCost = ServiceCostCalculatorStandard::calculateServiceCostPlain(
            $cartData->read()['positionsValueNet'] ?? 0.0,
            $service['costCalcMethod'] ?? ServiceCostCalcMethodEnum::FIXED_PRICE->value,
            $service['costCalcParam'] ?? 0.0
        );

        $result = new ServiceCostInfo();
        $result->setCostNet($serviceCost);

        return $result;
    }

    private function getService(int $serviceId): array
    {
        $service = ($this->listByFiltersService)(
                [new QueryFilter('id', $serviceId, QueryFilter::COMPARATOR_EQUAL)],
                [],
                ['id', 'costCalcMethod', 'costCalcParam']
        )->read();

        if (empty($service)) {
            throw new ObjectNotFoundException(
                sprintf('Obiekt Service nie istnieje. Id: %s', $serviceId)
            );
        }

        return current($service);
    }

}
