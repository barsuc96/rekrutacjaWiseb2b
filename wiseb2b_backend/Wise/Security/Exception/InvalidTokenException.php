<?php

declare(strict_types=1);

namespace Wise\Security\Exception;

use Wise\Core\Exception\CommonLogicException;

/**
 * Wyjątek związany z nieprawidłowym tokenem (albo niepoprawnym albo wygasłym)
 */
class InvalidTokenException extends CommonLogicException
{

}
