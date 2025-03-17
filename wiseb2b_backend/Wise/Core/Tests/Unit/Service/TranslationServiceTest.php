<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Service;

use Codeception\Attribute\DataProvider;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Wise\Core\Model\Translation;
use Wise\Core\Model\Translations;
use Wise\Core\Service\TranslationService;

class TranslationServiceTest extends Unit
{
    private SerializerInterface $serializer;
    private Stopwatch $stopwatch;

    public function _before(): void
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        /** @var SerializerInterface $serializer */
        $this->serializer = $symfony->grabService(SerializerInterface::class);
        $this->stopwatch = $symfony->grabService(Stopwatch::class);
    }

    #[DataProvider('getTranslationFieldForGivenLanguageCases')]
    public function testGetTranslationFieldForGivenLanguageOnArrayInput(
        string $fallbackLanguage,
        string $language,
        string $expected
    ): void {
        $items = [
            ['language' => 'pl', 'translation' => 'Polska'],
            ['language' => 'en', 'translation' => 'English'],
            ['language' => 'de', 'translation' => 'Deutsch'],
            ['language' => '', 'translation' => 'Empty'],
        ];

        $service = new TranslationService($this->serializer, $fallbackLanguage, $this->stopwatch);

        $actual = $service->getTranslationForField($items, $language);

        $this->assertSame($expected, $actual);
    }

    public function testGetTranslationFieldForGivenLanguageOnArrayInputWithoutEmptyLanguageTranslation(): void
    {
        $items = [
            ['language' => 'pl', 'translation' => 'Polska'],
            ['language' => 'en', 'translation' => 'English'],
            ['language' => 'de', 'translation' => 'Deutsch'],
        ];

        $service = new TranslationService($this->serializer, 'jp', $this->stopwatch);

        $actual = $service->getTranslationForField($items, 'es');

        $this->assertEmpty($actual);
    }

    public function testGetTranslationFieldForGivenLanguageOnArrayInputFilledWithWrongFields(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $items = [
            ['name' => 'Jan', 'quantity' => 2]
        ];

        $service = new TranslationService($this->serializer, 'jp', $this->stopwatch);

        $service->getTranslationForField($items, 'es');
    }

    #[DataProvider('getTranslationFieldForGivenLanguageCases')]
    public function testGetTranslationFieldForGivenLanguageOnCollectionInput(
        string $fallbackLanguage,
        string $language,
        string $expected
    ): void {
        $items = new Translations([
            (new Translation())->setLanguage('pl')->setTranslation('Polska'),
            (new Translation())->setLanguage('en')->setTranslation('English'),
            (new Translation())->setLanguage('de')->setTranslation('Deutsch'),
            (new Translation())->setLanguage('')->setTranslation('Empty'),
        ]);

        $service = new TranslationService($this->serializer, $fallbackLanguage, $this->stopwatch);

        $actual = $service->getTranslationForField($items, $language);

        $this->assertSame($expected, $actual);
    }

    protected function getTranslationFieldForGivenLanguageCases(): \Generator
    {
        yield 'select polish which exists' => ['pl', 'pl', 'Polska'];
        yield 'select english which exists' => ['pl', 'en', 'English'];
        yield 'select spanish which doesn\'t exist, take fallback' => ['pl', 'es', 'Polska'];
        yield 'select spanish which doesn\'t exist, fallback doesn\'t exist too' => ['jp', 'es', 'Empty'];
    }
}
