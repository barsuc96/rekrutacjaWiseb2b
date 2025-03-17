<?php

namespace Wise\User\ApiUi\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Cart\Domain\Cart\CartRepositoryInterface;
use Wise\Cart\Domain\Interfaces\CartMapperToOrderServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsService;
use Wise\Core\Service\CommonListParams;
use Wise\User\ApiUi\Service\Interfaces\GetTermsServiceInterface;
use Wise\User\Service\Terms\Interfaces\ListTermsServiceInterfaces;

/**
 * Zwraca treść regulaminów
 */
class GetTermsService extends AbstractGetDetailsService implements GetTermsServiceInterface
{
    const SECTION_AGREEMENT_SYMBOL = 'USER_REGULATIONS';

    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListTermsServiceInterfaces $service,
    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $serviceDto = ($this->service)(new CommonListParams());
        return $serviceDto->read();
    }
}
