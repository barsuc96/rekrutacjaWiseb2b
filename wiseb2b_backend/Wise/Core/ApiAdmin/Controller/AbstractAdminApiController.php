<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Controller;

use Wise\Core\Api\Controller\AbstractController;

/**
 * Klasa bazowa dla kontrolerów API ADMIN
 */
abstract class AbstractAdminApiController extends AbstractController
{
    const AREA_OPEN_API = 'api_admin_v2';

    protected array $requiredApiScopes = [];
    protected string $scope = 'admin-api';

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
     * Zwraca area OpenApi dla ADMIN API
     * @return string
     */
    public function getAreaOpenApi(): string
    {
        return static::AREA_OPEN_API;
    }
}
