<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Object;

use Wise\Core\Model\Collection;
use Wise\Core\Model\Translation;
use Wise\Core\Model\Translations;

class TranslationsHelper
{
    public static function convert(null|Collection|array $field): Collection|array|null
    {
        if (is_array($field)) {
            $translations = new Translations();
            foreach ($field as $translationData) {
                if (is_object($translationData)) {
                    $translation = (new Translation())
                        ->setTranslation($translationData->getTitle())
                        ->setLanguage($translationData->getLanguage());

                    $translations[] = $translation;
                } elseif (isset($translationData['translation'], $translationData['language'])) {
                    $translation = (new Translation())
                        ->setTranslation($translationData['translation'])
                        ->setLanguage($translationData['language']);

                    $translations[] = $translation;
                }
            }
            return $translations;
        }

        return $field;
    }
}
