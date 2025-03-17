<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Object;

use Wise\Core\Model\BlobTranslation;
use Wise\Core\Model\BlobTranslations;

class BlobTranslationHelper
{
    public static function convert(null|BlobTranslations|array $field): BlobTranslations|array|null
    {
        if (is_array($field)) {
            $blobTranslations = new BlobTranslations();
            foreach ($field as $blobTranslationData) {
                if (isset($blobTranslationData['blobText'], $blobTranslationData['language'])) {
                    $blobTranslation = (new BlobTranslation())
                        ->setBlobText($blobTranslationData['blobText'])
                        ->setLanguage($blobTranslationData['language']);

                    $blobTranslations[] = ($blobTranslation);
                }
            }
            return $blobTranslations;
        }

        return $field;
    }
}
