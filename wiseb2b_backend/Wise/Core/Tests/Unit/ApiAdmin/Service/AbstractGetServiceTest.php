<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\ApiAdmin\Service;

use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Symfony\Component\HttpFoundation\InputBag;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\Tests\Unit\ApiAdmin\Service\Stubs\StubGetAdminApiDto;
use Wise\Core\Tests\Unit\ApiAdmin\Service\Stubs\StubGetService;

final class AbstractGetServiceTest extends Unit
{
    private StubGetService $service;

    public function _before(): void
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        /** @var AdminApiShareMethodsHelper $requestUuidService */
        $adminApiShareMethodHelper = $symfony->grabService(AdminApiShareMethodsHelper::class);

        $this->service = new StubGetService($adminApiShareMethodHelper);
    }

    public function testBooleanQueryParamIsParsedProperly(): void
    {
        $query = new InputBag([
            'testBool' => 'false',
            'isTestBool' => 'true',
            'testString' => 'Sample',
            'testFloat' => 21.37,
        ]);

        $actual = $this->service->process($query, [], StubGetAdminApiDto::class);

        $response = json_decode($actual->getContent(), true);

        $this->assertFalse($response['input_parameters']['test_bool']);
        $this->assertTrue($response['input_parameters']['is_test_bool']);
        $this->assertSame('Sample', $response['input_parameters']['test_string']);
        $this->assertSame(21.37, $response['input_parameters']['test_float']);
    }
}
