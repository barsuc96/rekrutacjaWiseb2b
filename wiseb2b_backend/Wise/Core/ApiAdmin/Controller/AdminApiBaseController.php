<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Controller;

use Wise\Core\Controller\CommonBaseController;

/**
 * Abstract, po którym dziedziczą wszystkie kontrolery API Admin, zabezpiecza przed nieautoryzowanym dostępem do API
 *
 * @deprecated - zastąpiona przez \Wise\Core\Endpoint\Controller\ApiAdmin\AbstractAdminApiController
 *
 *  ### UWAGA! ZWRÓĆ UWAGĘ, ŻE OD TEJ PORY KAŻDA METODA HTTP MA SWÓJ DEDYKOWANY KONTROLER (SPRAWDŹ KATALOG Wise/Core/Endpoint/Controller/ApiAdmin/)
 *
 */
abstract class AdminApiBaseController extends CommonBaseController
{
    protected array $requiredApiScopes = [];

    /**
     * @return array
     */
    public function getRequiredApiScopes(): array
    {
        return $this->requiredApiScopes;
    }
}
