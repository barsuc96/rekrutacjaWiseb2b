<?php

declare(strict_types=1);

namespace Wise\Receiver\Tests\Support;

use Wise\Core\Tests\Support\Trait\AdminApiExampleEntityTrait;
use Wise\Core\Tests\Support\Trait\AdminApiTesterTrait;
use Wise\Core\Tests\Support\Trait\TesterUtilsTrait;

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
class AdminApiTester extends \Codeception\Actor
{
    use _generated\AdminApiTesterActions, AdminApiTesterTrait, TesterUtilsTrait, AdminApiExampleEntityTrait;

    /**
     * Define custom actions here
     */
}
