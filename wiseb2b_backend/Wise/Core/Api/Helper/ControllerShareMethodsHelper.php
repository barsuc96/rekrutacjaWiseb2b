<?php

declare(strict_types=1);

namespace Wise\Core\Api\Helper;

use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\Api\Helper\Interfaces\OpenApiAliasResolverInterface;

/**
 * Klasa udostępniająca metody pomocnicze dla endpointów (każdego rodzaju API)
 * Wykorzystywana w klasach kontrolerów
 * Służy do udostępniania serwisów dla kontrolerów. Pozwala na uniknięcie duplikacji kodu w klasach kontrolerów oraz udostępnienie klas
 */
class ControllerShareMethodsHelper implements ControllerShareMethodsHelperInterface
{
    public function __construct(
        public OpenApiAliasResolverInterface $openApiAliasResolver
    ){}
}
