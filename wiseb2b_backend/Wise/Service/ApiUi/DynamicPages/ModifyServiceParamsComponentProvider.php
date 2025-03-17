<?php

namespace Wise\Service\ApiUi\DynamicPages;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\Service\CommonDetailsParams;
use Wise\DynamicUI\ApiUi\Service\PageDefinition\ComponentType\AbstractComponentDefinitionProvider;
use Wise\DynamicUI\ApiUi\Service\PageDefinition\ComponentType\ComponentDefinitionParams;
use Wise\DynamicUI\ApiUi\Service\PageDefinition\ComponentType\ComponentDefinitionProviderInterface;
use Wise\DynamicUI\ApiUi\Service\PageDefinition\ComponentType\DynamicUiComponent;
use Wise\DynamicUI\ApiUi\Service\PageDefinition\ComponentType\Fields\EditPanelFieldDefinition;
use Wise\DynamicUI\ApiUi\Service\PageDefinition\ComponentType\Fields\LoadDataParamsDefinition;
use Wise\Pricing\Domain\Promotion\PromotionRepositoryInterface;
use Wise\Pricing\Service\DeliveryPaymentCost\Interfaces\GetDeliveryPaymentCostDetailsServiceInterface;
use Wise\Service\Service\Service\Interfaces\GetServiceDetailsServiceInterface;

#[AutoconfigureTag(name: 'dynamic_ui.page_definition_components')]
class ModifyServiceParamsComponentProvider extends AbstractComponentDefinitionProvider implements ComponentDefinitionProviderInterface
{
    protected string|null $componentSymbol = 'EDIT_PANEL_MODIFY_SERVICE_PARAMS';

    public function __construct(
        private readonly GetDeliveryPaymentCostDetailsServiceInterface $getDeliveryPaymentCostDetailsService,
        private readonly GetServiceDetailsServiceInterface $getServiceDetailsService,
        private readonly PromotionRepositoryInterface $promotionRepository,
    ){}

    public function __invoke(
        ComponentDefinitionParams $params,
        DynamicUiComponent $componentDefinition
    ): DynamicUiComponent
    {
        $componentDefinition->setFields([]);
        $serviceDetails = null;

        if(!empty($params->getParams()['paramServiceId'])){
            $paramsDetails = new CommonDetailsParams();
            $paramsDetails
                ->setId(intval($params->getParams()['paramServiceId']))
                ->setFields([
                    'id' => 'id',
                    'costCalcParam' => 'costCalcParam',
                    'costCalcMethod' => 'costCalcMethod',
                ])
                ->setExecuteExceptionWhenEntityNotExists(false);

            $serviceDetails = ($this->getServiceDetailsService)($paramsDetails)->read();
        }

        $this->addLoadDataParams($componentDefinition, $params->getParams());

        $serviceCalcMethod = $params?->getParams()['costCalcMethod'] ?? $serviceDetails['costCalcMethod'] ?? null;

        if($serviceCalcMethod === null){
            return $componentDefinition;
        }

        if ($serviceCalcMethod == 1) {
            $field = (new EditPanelFieldDefinition())
                ->setLabel('Wprowadź stałą cenę dostawy')
                ->setType('float')
                ->setFieldSymbol('cost_calc_param');

            if(!empty($serviceDetails)){
                $field->setValue($serviceDetails['costCalcParam']);
            }

            $fields = [$field];
        } else {
            $field = (new EditPanelFieldDefinition())
                ->setLabel('Wprowadź % wartości koszyka')
                ->setType('float')
                ->setFieldSymbol('cost_calc_param');

            if(!empty($serviceDetails)){
                $field->setValue($serviceDetails['costCalcParam']);
            }

            $fields = [$field];
        }

        $componentDefinition->setFields($fields);


        return $componentDefinition;
    }

    /**
     * Dodaje parametry do ładowania danych
     * @param DynamicUiComponent $component
     * @param array|null $parameters
     * @return void
     */
    protected function addLoadDataParams(DynamicUiComponent $component, ?array $parameters): void
    {
        if(array_key_exists('mode', $parameters)){
            $mode = $parameters['mode'];
        } else {
            $mode = 'NEW';
        }

        if($mode == 'EDIT'){
            $loadData = new LoadDataParamsDefinition();
            $loadData->setGetUrl('/panel/services/service/:param_service_id');

            $component->setLoadDataParams($loadData);
        }
    }
}
