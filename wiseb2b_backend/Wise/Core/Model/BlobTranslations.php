<?php

declare(strict_types=1);

namespace Wise\Core\Model;

class BlobTranslations extends Collection implements MergableInterface
{
    public function getBlobTranslations(): self
    {
        return $this;
    }
    public function merge(array $data = null): void
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $translationData) {
            if (isset($translationData['blobText'], $translationData['language'])) {
                $this[] = (new BlobTranslation())
                    ->setBlobText($translationData['blobText'])
                    ->setLanguage($translationData['language'])
                ;
            }
        }
    }

    public function deleteByLanguage(string $language): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->getLanguage() === $language) {
                unset($this->items[$key]);
                break;
            }
        }
    }
}
