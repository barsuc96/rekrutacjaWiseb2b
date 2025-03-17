<?php

declare(strict_types=1);

namespace Wise\Core\Service;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Webmozart\Assert\Assert;
use Wise\Core\Model\BlobTranslation;
use Wise\Core\Model\BlobTranslations;
use Wise\Core\Model\Translations;

final class TranslationService
{
    private const BLOB_TEXT_FIELD = 'blobText';
    private const BLOB_TEXT_FIELD_AFTER_SERIALIZED = 'blob_text';

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly string $fallbackContentLanguage,
        private readonly Stopwatch $stopwatch
    ) {}

    /**
     * Zwraca tłumaczenie dla przekazanego języka.
     * @param array|Translations|null $items
     * @param string $contentLanguage
     * @return string|null
     * @throws ExceptionInterface
     */
    public function getTranslationForField(null|array|Translations $items, string $contentLanguage): ?string
    {
        $this->stopwatch->start('TranslationService::getTranslationForField');
        $contentLanguage = strtolower($contentLanguage);

        if($items === null || (is_array($items) && empty($items))) {
            $this->stopwatch->stop('TranslationService::getTranslationForField');
            return '';
        }

        if (is_object($items) || is_object(reset($items))) {
            $items = $this->serializer->normalize($items);
        }

        foreach ($items as $item) {
            Assert::isArray($item);
            Assert::keyExists($item, 'language');
            Assert::keyExists($item, 'translation');
        }

        // Jeśli jest tłumaczenie dla przekazanego języka to go zwracamy
        $item = array_filter($items, fn(array $i) => $i['language'] === $contentLanguage);
        if (!empty($item)) {
            $this->stopwatch->stop('TranslationService::getTranslationForField');
            return current($item)['translation'];
        }

        // Jeśli nie ma tłumaczenia dla przekazanego języka to zwracamy dla języka domyślnego
        $item = array_filter($items, fn(array $i) => $i['language'] === $this->fallbackContentLanguage);
        if (!empty($item)) {
            $this->stopwatch->stop('TranslationService::getTranslationForField');
            return current($item)['translation'];
        }

        // Jeśli nie jest określony language (pusty string) to zwracamy pierwszy element
        $item = array_filter($items, fn(array $i) => $i['language'] === '');
        if (!empty($item)) {
            $this->stopwatch->stop('TranslationService::getTranslationForField');
            return current($item)['translation'];
        }

        $this->stopwatch->stop('TranslationService::getTranslationForField');
        return '';
    }

    public function getBlobTextForField(null|array|BlobTranslations $items, string $contentLanguage): string
    {
        $contentLanguage = strtolower($contentLanguage);

        if($items === null || (is_array($items) && empty($items))) {
            return '';
        }

        if ($items instanceof BlobTranslations //
            || reset($items) instanceof BlobTranslation) {
            $items = $this->serializer->normalize($items);
            $fieldKey = self::BLOB_TEXT_FIELD_AFTER_SERIALIZED;
        } else {
            $fieldKey = self::BLOB_TEXT_FIELD;
        }

        foreach ($items as $item) {
            Assert::isArray($item);
            Assert::keyExists($item, 'language');
            Assert::keyExists($item, $fieldKey);
        }

        $item = array_filter($items, fn(array $i) => $i['language'] === $contentLanguage);
        if (!empty($item)) {
            return current($item)[$fieldKey];
        }

        $item = array_filter($items, fn(array $i) => $i['language'] === $this->fallbackContentLanguage);
        if (!empty($item)) {
            return current($item)[$fieldKey];
        }

        $item = array_filter($items, fn(array $i) => $i['language'] === '');
        if (!empty($item)) {
            return current($item)[$fieldKey];
        }

        return '';
    }

    /**
     * Sprawdza, czy zawiera choć jedną translację
     * @param array|Translations|null $items
     * @return bool
     * @throws ExceptionInterface
     */
    public function checkIsNotEmpty(null|array|Translations $items): bool
    {
        if($items === null || (is_array($items) && empty($items))) {
            $this->stopwatch->stop('TranslationService::getTranslationForField');
            return false;
        }

        if (is_object($items) || is_object(reset($items))) {
            $items = $this->serializer->normalize($items);
        }

        foreach ($items as $item) {
            Assert::isArray($item);
            Assert::keyExists($item, 'language');
            Assert::keyExists($item, 'translation');
        }

        // Sprawdzamy, czy translacja ma wymagane pola i zawiera choć jedną translacje
        $hasTranslation = false;
        foreach ($items as $item) {
            if (!empty($item['language']) && !empty($item['translation']) && $item['translation'] !== ' ') {
                $hasTranslation = true;
                break;
            }
        }

        return $hasTranslation;
    }
}
