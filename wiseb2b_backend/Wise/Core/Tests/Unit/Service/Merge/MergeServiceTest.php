<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service\Merge;

use Codeception\Attribute\Examples;
use Codeception\Attribute\Skip;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use DateTime;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Wise\Core\Model\Translations;
use Wise\Core\Service\Merge\MergeService;
use Wise\Core\Service\Merge\TranslationsMergeService;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\CustomMergableObject;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MergableWithNestedArrayObjects;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\Money;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MoneyMergableObject;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\CustomMergeService;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MergableBoolean;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MergableCollection;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MergableDateTimeObject;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MergableGetSetObject;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MergableObjectWithoutConstructor;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MergablePublicObject;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MergableReadonlyObject;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MergableWithNested;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\MoneyMergeService;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\NotMergableObject;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\Phone;
use Wise\Core\Tests\Unit\Service\Merge\Stubs\TranslationObject;

final class MergeServiceTest extends Unit
{
    private MergeService $service;

    public function _before(): void
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        /** @var PropertyAccessorInterface $propertyAccessor */
        $propertyAccessor = $symfony->grabService(PropertyAccessorInterface::class);

        $this->service = new MergeService($propertyAccessor, [
            new CustomMergeService(),
            new MoneyMergeService(),
            new TranslationsMergeService(),
        ]);
    }

    public function testNotMergableObjectIsSkipped(): void
    {
        $this->expectException(\Exception::class);

        $object = new NotMergableObject('John', 32, '+298 414000');

        $this->service->merge($object, ['name' => 'Jane', 'age' => 33], false);
    }

    public function testMergableObjectMergeCorrectlyWhilePublicFields(): void
    {
        $object = new MergablePublicObject('John', 32, '+298 414000');

        $this->service->merge($object, ['name' => 'Jane', 'age' => 33], false);

        $this->assertSame('Jane', $object->name);
        $this->assertSame(33, $object->age);
        $this->assertSame('+298 414000', $object->phone);
    }

    public function testMergableObjectWithoutConstructorMergeCorrectly(): void
    {
        $object = new MergableObjectWithoutConstructor();

        $this->service->merge($object, ['name' => 'Jane', 'age' => 33], false);

        $reflector = new \ReflectionClass($object);
        $this->assertTrue($reflector->getProperty('name')->isInitialized($object));
        $this->assertSame('Jane', $object->getName());
        $this->assertTrue($reflector->getProperty('age')->isInitialized($object));
        $this->assertSame(33, $object->getAge());
        $this->assertFalse($reflector->getProperty('phone')->isInitialized($object));
    }

    public function testMergableObjectMergeCorrectlyWhileGetSetFields(): void
    {
        $object = new MergableGetSetObject('John', 32, '+298 414000');

        $this->service->merge($object, ['name' => 'Jane', 'age' => 33], false);

        $this->assertSame('Jane', $object->getName());
        $this->assertSame(33, $object->getAge());
        $this->assertSame('+298 414000', $object->getPhone());
    }

    public function testMergableObjectMergeCorrectlyButMoreParametersGiven(): void
    {
        $this->expectException(\Exception::class);

        $object = new MergableGetSetObject('John', 32, '+298 414000');

        $this->service->merge($object, ['name' => 'Jane', 'additionalParameter' => true], false);
    }

    public function testMergableObjectWithReadonlyPropertiesWillNotMerge(): void
    {
        $this->expectException(\Exception::class);

        $object = new MergableReadonlyObject('John', 32, '+298 414000');

        $this->service->merge($object, ['name' => 'Jane'], false);
    }

    #[
        Examples(true, true, true),
        Examples(true, false, false),
        Examples(false, true, true),
        Examples(false, false, false),
    ]
    public function testBooleanIsMergedCorrectly(bool $initial, bool $merged, bool $expected): void
    {
        $object = new MergableBoolean($initial);

        $this->service->merge($object, ['check' => $merged], false);

        $this->assertSame($expected, $object->isCheck());
    }

    #[
        Examples(null, new DateTime('2023-06-01 13:25:00'), '1685625900'),
        Examples(new DateTime('2023-05-31 15:17:50'), new DateTime('2023-06-01 13:25:00'), '1685625900'),
        Examples(new DateTime('2023-05-31 15:17:50'), null, null),
    ]
    public function testDatetimeIsMergedCorrectly(?DateTime $initial, ?DateTime $merged, ?string $expected): void
    {
        $object = new MergableDateTimeObject($initial);

        $this->service->merge($object, ['timeSensitiveData' => $merged], false);

        $this->assertSame($expected, $object->timeSensitiveData?->format('U'));
    }

    #[
        Examples(null, '2023-06-01 13:25:00', '1685625900'),
        Examples(new DateTime('2023-05-31 15:17:50'), '2023-06-01 13:25:00', '1685625900')
    ]
    public function testDateTimeAsStringMergingCorrectly(
        null|DateTime $initial,
        null|DateTime|string $merged,
        ?string $expected
    ): void {
        $object = new MergableDateTimeObject($initial);

        $this->service->merge($object, ['timeSensitiveData' => $merged], false, true);

        $this->assertSame($expected, $object->timeSensitiveData?->format('U'));
    }

    public function testNestedObjectAllowsToMergeRecursively(): void
    {
        $object = new MergableWithNested('John', new Phone('+298', '414000'));

        $this->service->merge($object, ['name' => 'Jane', 'phone' => ['number' => '123456']], false);

        $this->assertSame('Jane', $object->getName());
        $this->assertSame('+298', $object->getPhone()->countryCode);
        $this->assertSame('123456', $object->getPhone()->number);
    }

    public function testArrayNestedObjectsAllowsToMergeRecursively(): void
    {
        $object = new MergableWithNestedArrayObjects('John', null);

        $this->service->merge(
            $object,
            ['name' => 'Jane', 'phones' => [['number' => '123456789'], ['number' => '987654321']]]
        );

        $this->assertSame('Jane', $object->getName());
        // oczekuje obiektu, a dostaję array - wyrzuci błąd
        $this->assertSame('123456789', $object->getPhones()[0]->getNumber());
        $this->assertSame('987654321', $object->getPhones()[1]->getNumber());
    }

    public function testNestedObjectsWithNullAllowsToMergeRecursivelyAndStoresCorrectly(): void
    {
        $object = new MergableWithNested('John', null);

        $this->service->merge(
            $object,
            ['name' => 'Jane', 'phone' => ['countryCode' => '+49', 'number' => '123456']],
            false
        );

        $this->assertSame('Jane', $object->getName());
        $this->assertSame('+49', $object->getPhone()->getCountryCode());
        $this->assertSame('123456', $object->getPhone()->getNumber());
    }

    #[
        Examples(true, ['John', 'Jane', 'Jack', 'Jill']),
        Examples(false, ['Jack', 'Jill']),
    ]
    public function testArrayOfDataIsMergedCorrectly(bool $removeMissingInNestedObjects, array $expected): void
    {
        $object = new MergableCollection(['John', 'Jane']);

        $this->service->merge($object, ['items' => ['Jack', 'Jill']], $removeMissingInNestedObjects);

        $this->assertSame($expected, $object->getItems());
    }

    #[
        Examples(['Jack', 'Jill'], [0, 1]),
        Examples(['x' => [1, 2]], ['x']),
        Examples([], [])
    ]
    public function testArrayOfDataIsMergedToNullInitValueCorrectly(array $inputData, array $expectedKeys): void
    {
        $object = new MergableCollection(null);

        $this->service->merge($object, ['items' => $inputData]);

        $this->assertSame($expectedKeys, array_keys($object->getItems()));
    }

    public function testCustomMergableObjectWorksCorrectly(): void
    {
        $object = new CustomMergableObject('John', 32, '+298 414000');

        $this->service->merge($object, ['name' => 'Jane', 'yearOfBirth' => 1970], false);

        $this->assertSame('Jane', $object->name);
        $this->assertSame(53, $object->age);
    }

    public function testMoneyMergableObjectWithoutFillingNullValue(): void
    {
        $object = new MoneyMergableObject(new Money(101, 'EUR'));

        $this->service->merge($object, ['money' => ['currency' => 'PLN']], false);

        $this->assertSame(454.5, $object->money->amount);
        $this->assertSame('PLN', $object->money->currency);
    }

    public function testMoneyMergableObjectWithFillingNullValue(): void
    {
        $object = new MoneyMergableObject(new Money(100, 'EUR'));

        $this->service->merge($object, ['nullableMoney' => ['amount' => 30, 'currency' => 'EUR']], false);

        $this->assertSame(100.0, $object->money->amount);
        $this->assertSame('EUR', $object->money->currency);
        $this->assertSame(30.0, $object->nullableMoney->amount);
        $this->assertSame('EUR', $object->nullableMoney->currency);
    }

    public function testNullValueOnNestedMergableObjectWorksCorrectly(): void
    {
        $object = new MergableWithNested('John', new Phone('+298', '414000'));

        $this->service->merge($object, ['phone' => null], false);

        $this->assertSame('John', $object->getName());
        $this->assertNull($object->getPhone());
    }

    public function testTranslationService(): void
    {
        $object = new TranslationObject(new Translations());

        $this->service->merge($object, [
            'name' => [
                ['language' => 'en', 'translation' => 'John'],
                ['language' => 'pl', 'translation' => 'Jan'],
            ],
            'description' => [
                ['language' => 'en', 'translation' => 'Description'],
                ['language' => 'pl', 'translation' => 'Opis'],
                ['language' => 'de', 'translation' => 'Beschreibung'],
            ]
        ], false);

        $this->assertCount(2, $object->name);
        $translation = $object->name[0];
        $this->assertSame('en', $translation->getLanguage());
        $this->assertSame('John', $translation->getTranslation());
        $this->assertCount(3, $object->description);
    }

    public function testCanSkipKeysWhichNotExistsInObject(): void
    {
        $object = new MergableBoolean(true);

        $this->service->merge($object, ['check' => true, 'notExistInObject' => false], false, true);

        $this->assertTrue($object->isCheck());
    }

    public function testCannotSkipKeysWhichNotExistsInObject(): void
    {
        $this->expectException(\RuntimeException::class);

        $object = new MergableBoolean(true);

        $this->service->merge($object, ['check' => true, 'notExistInObject' => false]);
    }
}
