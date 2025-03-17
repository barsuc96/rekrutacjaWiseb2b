<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Controller\Core;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Controller\AbstractController;

/**
 * Klasa bazowa dla kontrolerów API UI
 */
abstract class AbstractUiApiController extends AbstractController
{
    const AREA_OPEN_API = 'api_ui_v2';

    protected array $requiredApiScopes = [];
    protected string $scope = 'ui-api';

    /**
     * Zwraca Api Scope
     * @return array
     */
    public function getRequiredApiScopes(): array
    {
        return $this->requiredApiScopes;
    }

    /**
     * Zwraca scope (dodatkowa funkcjonalność, aby odróżnić od siebie rodzaje endpointów)
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * Zwraca area OpenApi dla UI API
     * @return string
     */
    public function getAreaOpenApi(): string
    {
        return static::AREA_OPEN_API;
    }


    /**
     * Zwraca parametry zapytania
     * @param Request $request
     * @return InputBag
     */
    protected function getParameters(Request $request): InputBag
    {
        foreach ($request->attributes->get('_route_params') as $key => $value) {
            $request->query->add([$key => $value]);
        }

        return $request->query;
    }
}
