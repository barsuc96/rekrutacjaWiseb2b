<?php

namespace Wise\Service\Service\Driver\DeliveryStandard;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Wise\Cart\Service\Cart\Interfaces\CartHelperInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Delivery\Service\DeliveryMethod\Interfaces\GetDeliveryMethodDetailsServiceInterface;
use Wise\Pricing\Domain\DeliveryPaymentCost\DeliveryPaymentCost;
use Wise\Pricing\Service\DeliveryPaymentCost\DeliveryPaymentCostHelper;
use Wise\Pricing\Service\DeliveryPaymentCost\Interfaces\DeliveryPaymentCostHelperInterface;
use Wise\Service\Domain\ServiceCostProviderInterface;
use Wise\Service\Domain\ServiceCostInfo;
use Wise\Service\WiseServiceExtension;

/**
 * Serwis do wyliczania kosztu usługi na podstawie danych
 * zawartych w tabeli delivery_payment_cost z perspektywy dostawy
 * (deliveryPaymentCost ma zarówno dane do wyliczania dla dostawy jak i płatności)
 */
class DeliveryStandardCostProvider extends AbstractDeliveryStandardProvider implements ServiceCostProviderInterface
{
    public function __construct(
        private readonly DeliveryPaymentCostHelperInterface $deliveryPaymentCostHelper,
        private readonly CartHelperInterface $cartHelper,
        private readonly GetDeliveryMethodDetailsServiceInterface $deliveryMethodDetailsService,
        private readonly ContainerBagInterface $configParams,
    ){}

    public function __invoke(int $serviceId, CommonServiceDTO $cartData): ServiceCostInfo
    {
        $cart = $cartData->read();
        $country = $this->cartHelper->getDeliveryCountryFromDTO($cartData)->read();
        $isDropshipping = $cartData->read()['dropshipping'];

        $cart['positionsValueNet'] = $cart['positionsValueNet'] ?? 0;

        $deliveryPaymentCost = $this->deliveryPaymentCostHelper->findDeliveryPaymentCostByCartParams(
            $this->getDeliveryMethodId($serviceId),
            $this->getPaymentMethodId($cart),
            $country['id'] ?? null,
            $cart['currency'],
            $isDropshipping
        );

        // Jeśli nie znaleziono kosztów dostawy
        if($deliveryPaymentCost === null){
            $result = new ServiceCostInfo();
            $result->setCostNet(null);

            return $result;
        }

        // Jeśli dostawa jest darmowa
        if ($deliveryPaymentCost->freeDeliveryApplicable($cart['positionsValueNet'])){
            $result = new ServiceCostInfo();
            $result->setCostNet(0);

            return $result;
        }

        $baseValue = $cart['positionsValueNet'];

        $deliveryValue = DeliveryPaymentCostHelper::calculateServiceCost(
            $baseValue,
            $deliveryPaymentCost->getDeliveryCalcMethod(),
            $deliveryPaymentCost->getDeliveryCalcParam()
        );

        // Przeliczenie dodatkowych opcji metody dostawy
        if($deliveryValue !== null){
            $this->calculateAdditionalOptionsCost($cartData, $deliveryValue);
        }

        $result = new ServiceCostInfo();
        $result->setCostNet($deliveryValue);

        return $result;
    }

    /**
     * Oblicza koszt sumaryczny dodatkowych opcji dostawy
     * @param CommonServiceDTO $cartData
     * @param float $costDeliveryMethod
     */
    protected function calculateAdditionalOptionsCost(CommonServiceDTO $cartData, float &$costDeliveryMethod)
    {
        $cartData = $cartData->read();

        if(!empty($cartData['deliveryOptions'])){

            // Obliczamy koszt dodatkowych opcji dostawy (o ile są aktywne)
            foreach ($cartData['deliveryOptions'] as $option){
                if(array_key_exists('value', $option) && array_key_exists('symbol', $option) && $option['value'] === true){
                    $costDeliveryMethod += $this->getDeliveryOptionCostBySymbol($option['symbol']);
                }
            }
        }
    }

    /**
     * Zwraca koszt dodatkowej opcji dostawy na podstawie symbolu
     * @param string $symbol
     * @return float
     */
    protected function getDeliveryOptionCostBySymbol(string $symbol): float
    {
        return 0.0;
    }

    /**
     * Zwraca dane metody dostawy
     * @param int $serviceId
     * @return array
     */
    protected function getDeliveryMethod(int $serviceId): array
    {
        $params = new CommonDetailsParams();
        $params
            ->setFilters([
                new QueryFilter('serviceId', $serviceId),
                new QueryFilter('isActive', true)
            ])
            ->setFields([
                'id' => 'id',
                'serviceId' => 'serviceId'
            ]);

        return ($this->deliveryMethodDetailsService)($params)->read();
    }

    /**
     * Zwraca id metody dostawy
     * @param int $serviceId
     * @return int|null
     */
    protected function getDeliveryMethodId(int $serviceId): ?int
    {
        $deliveryMethod = $this->getDeliveryMethod($serviceId);
        $deliveryMethodId = null;

        if(!empty($deliveryMethod)){
            $deliveryMethodId = $deliveryMethod['id'];
        }

        if($deliveryMethodId === null){
            $deliveryMethodId = $this->getDefaultIds()['default_delivery_method_id'];
        }

        return $deliveryMethodId;
    }

    /**
     * Zwraca id metody płatności
     * @param array|null $cart
     * @return int|null
     */
    private function getPaymentMethodId(?array $cart): ?int
    {
        $paymentMethodId = $cart['paymentMethodId'] ?? null;

        if($paymentMethodId === null){
            $paymentMethodId = $this->getDefaultIds()['default_payment_method_id'];
        }

        return $paymentMethodId;
    }

    /**
     * Zwraca domyślne id metody dostawy i płatności
     * @return array|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getDefaultIds(): ?array
    {
        $config = $this->configParams->get(WiseServiceExtension::getExtensionAlias());
        return $config['service_cost_provider'] ?? null;
    }
}
