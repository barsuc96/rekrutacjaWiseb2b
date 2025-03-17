<?php

declare(strict_types=1);

namespace Wise\Core\Model;

/** @implements Collection<Translation> */
class Translations extends Collection implements MergableInterface
{
    public function getTranslations(): self
    {
        return $this;
    }

    public function merge(array $data): void
    {
        foreach ($data as $translationData) {
            if (isset($translationData['translation'], $translationData['language'])) {
                $this[] = (new Translation())
                    ->setTranslation($translationData['translation'])
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
