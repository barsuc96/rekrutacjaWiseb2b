<?php

namespace Wise\Core\Tests\Unit\Helper\Date;

use Codeception\Test\Unit;
use DateTime;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use TypeError;
use Wise\Core\Helper\Date\CommonDateTimeNormalizer;

class CommonDateTimeNormalizerTest extends Unit
{

    const SERIALIZER_CONTEXT = [
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'
    ];

    private CommonDateTimeNormalizer $dateTimeNormalizer;

    public function _before(): void
    {
        $this->dateTimeNormalizer = new CommonDateTimeNormalizer();
    }

    public function testDateTimeFromStringIsNormalizedCorrectly(): void
    {
        $denormalizedForm = $this->dateTimeNormalizer->denormalize('2022-04-08 00:00:00', DateTime::class);

        $this->assertSame('1649376000', $denormalizedForm->format('U'));
    }

    public function testDateTimeFromArrayIsNormalizedCorrectly(): void
    {
        $denormalizedForm = $this->dateTimeNormalizer->denormalize(
            ['date' => '2022-04-08 00:00:00', "timezone_type" => 3, "timezone" => "UTC"],
            DateTime::class
        );

        $this->assertSame('1649376000', $denormalizedForm->format('U'));
    }

    public function testDateTimeObjectNormalizationThrowsError(): void
    {
        $this->expectException(TypeError::class);
        $this->dateTimeNormalizer->denormalize(
            new DateTime('2022-04-08 00:00:00'),
            DateTime::class
        );
    }
}
