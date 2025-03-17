<?php

declare(strict_types=1);

namespace Wise\I18n\ApiUi\Service\Layout;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\I18n\ApiUi\Dto\Layout\LayoutLanguagesResponseDto;
use Wise\I18n\ApiUi\Service\Layout\Interfaces\GetLayoutLanguagesServiceInterface;
use Wise\I18n\Service\Layout\Interfaces\ListLanguagesServiceInterface;

class GetLayoutLanguagesService extends AbstractGetService implements GetLayoutLanguagesServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListLanguagesServiceInterface $service,
    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array {
        $fields = (new LayoutLanguagesResponseDto())->mergeWithMappedFields([]);
        $serviceDtoData = ($this->service)()->read();

        return (new LayoutLanguagesResponseDto())->resolveMappedFields($serviceDtoData, $fields);
    }
}
