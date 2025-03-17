<?php

namespace Wise\Service\Service\Driver\PaymentStandard;

use Wise\Cart\Service\Cart\Interfaces\CartHelperInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Pricing\Domain\DeliveryPaymentCost\DeliveryPaymentCost;
use Wise\Pricing\Service\DeliveryPaymentCost\DeliveryPaymentCostHelper;
use Wise\Pricing\Service\DeliveryPaymentCost\Interfaces\DeliveryPaymentCostHelperInterface;
use Wise\Service\Domain\ServiceCostProviderInterface;
use Wise\Service\Domain\ServiceCostInfo;

/**
 * Serwis do wyliczania kosztu usługi na podstawie danych
 * zawartych w tabeli delivery_payment_cost z perspektywy płatności
 * (deliveryPaymentCost ma zarówno dane do wyliczania dla dostawy jak i płatności)
 */
class PaymentStandardCostProvider extends AbstractPaymentStandardProvider implements ServiceCostProviderInterface
{
    public function __construct(
        private readonly DeliveryPaymentCostHelperInterface $deliveryPaymentCostHelper,
        private readonly CartHelperInterface $cartHelper,
    )
    {
    }

    public function __invoke(int $serviceId, CommonServiceDTO $cartData): ServiceCostInfo
    {
        $cart = $cartData->read();
        $country = $this->cartHelper->getDeliveryCountryFromDTO($cartData)->read();
        $isDropshipping = $cartData->read()['dropshipping'];

        $deliveryPaymentCost = $this->deliveryPaymentCostHelper->findDeliveryPaymentCostByCartParams(
            $cart['deliveryMethodId'],
            $cart['paymentMethodId'],
            $country['id']??null,
            $cart['currency'],
            $isDropshipping
        );

        if (false === $deliveryPaymentCost instanceof DeliveryPaymentCost)
        {
            return new ServiceCostInfo();
        }

        /*  TODO: probably we should add also services price without service connected to paymentMethod */
        $baseValue = $cart['positionsValueNet'] ?? 0;

        $paymentValue = DeliveryPaymentCostHelper::calculateServiceCost(
            $baseValue,
            $deliveryPaymentCost->getPaymentCalcMethod(),
            $deliveryPaymentCost->getPaymentCalcParam()
        );

        $result = new ServiceCostInfo();
        $result->setCostNet($paymentValue);

        return $result;
    }
}
