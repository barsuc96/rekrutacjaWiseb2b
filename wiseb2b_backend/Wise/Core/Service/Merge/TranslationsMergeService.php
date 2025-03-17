<?php

declare(strict_types=1);

namespace Wise\Core\Service\Merge;

use Wise\Core\Model\Translation;
use Wise\Core\Model\Translations;

/** @implements CustomMergeServiceInterface<Translations> */
final class TranslationsMergeService implements CustomMergeServiceInterface
{
    public function supports($object): bool
    {
        return $object instanceof Translations;
    }

    public function merge($object, array &$data, bool $mergeNestedObjects): void
    {
        $languagesToDelete = array_map(fn (Translation $t): string => $t->getLanguage(), $object->__toArray());

        foreach ($data as $i => $t) {
            if (isset($t['translation'], $t['language'])) {
                if ($translation = $this->findTranslationForLanguage($object, $t['language'])) {
                    $translation->setTranslation($t['translation']);
                } else {
                    $object->add(Translation::fromArray($t));
                }
            }

            $languagesToDelete = array_diff($languagesToDelete, [$t['language']]);
            unset($data[$i]);
        }

        if (!$mergeNestedObjects) {
            foreach ($languagesToDelete as $language) {
                $object->deleteByLanguage($language);
            }
        }
    }

    private function findTranslationForLanguage(Translations $translations, string $language): ?Translation
    {
        foreach ($translations as $translation) {
            if ($translation->getLanguage() === $language) {
                return $translation;
            }
        }

        return null;
    }
}
