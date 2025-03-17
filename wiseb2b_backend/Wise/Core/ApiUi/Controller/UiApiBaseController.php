<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Controller;

use Symfony\Component\Security\Core\Security;
use Wise\Core\Controller\CommonBaseController;

/**
 * Bazowy controller dla ApiUi
 *
 * @deprecated - zastąpiona przez \Wise\Core\Endpoint\Controller\ApiUi\AbstractUiApiController
 *
 * ### UWAGA! ZWRÓĆ UWAGĘ, ŻE OD TEJ PORY KAŻDA METODA HTTP MA SWÓJ DEDYKOWANY KONTROLER (SPRAWDŹ KATALOG Wise/Core/Endpoint/Controller/ApiUi/)
 *
 *
 */
abstract class UiApiBaseController extends CommonBaseController
{
    public function __construct(
        protected readonly Security $security
    ) {
    }
}
