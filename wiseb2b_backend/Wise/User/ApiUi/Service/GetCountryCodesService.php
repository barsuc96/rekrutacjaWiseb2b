<?php

namespace Wise\User\ApiUi\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Service\CommonListParams;
use Wise\User\ApiUi\Service\Interfaces\GetCountryCodesServiceInterface;
use Wise\User\Service\CountryCodes\Interfaces\ListCountryCodesServiceInterface;

class GetCountryCodesService extends AbstractGetService implements GetCountryCodesServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListCountryCodesServiceInterface $listCountryCodesService,
    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        return ($this->listCountryCodesService)(new CommonListParams())->read();
    }
}