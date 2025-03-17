<?php

declare(strict_types=1);

namespace Wise\I18n\ApiUi\Service\Layout\Interfaces;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\ServiceInterface\ApiUiGetServiceInterface;

interface GetLayoutLanguagesServiceInterface extends ApiUiGetServiceInterface
{
    public function get(ParameterBag $parameters): array;
}
