<?php

declare(strict_types=1);

namespace Wise\Core\Model;

class BlobTranslation extends AbstractModel
{
    protected string $language;
    protected string $blobText;

    public static function fromArray(array $data): BlobTranslation
    {
        $blobTextKey = isset($data['blob_text']) ? 'blob_text' : 'blobText';

        return (new self())
            ->setLanguage($data['language'])
            ->setBlobText($data[$blobTextKey]);
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getBlobText(): string
    {
        return $this->blobText;
    }

    public function setBlobText(string $blobText): self
    {
        $this->blobText = $blobText;

        return $this;
    }
}
