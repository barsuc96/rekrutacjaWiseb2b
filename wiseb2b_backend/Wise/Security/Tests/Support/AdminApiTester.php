<?php

declare(strict_types=1);

namespace Wise\Security\Tests\Support;

use Codeception\Actor;
use Wise\Core\Tests\Support\Trait\AdminApiTesterTrait;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class AdminApiTester extends Actor
{
    use _generated\AdminApiTesterActions, AdminApiTesterTrait;

    /**
     * Define custom actions here
     */
}
