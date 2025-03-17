<?php

namespace Wise\Core\Dto;

/**
 * Klasa służąca do zwrócenia informacji o wygenerowanych plikach
 */
class FileResponseDto extends AbstractResponseDto
{
    /**
     * Zawartość pliku w formie string, który zostanie później przekształcony na base_64
     * @var string
     */
    protected string $content;

    /**
     * Nazwa pliku wraz z rozszerzeniem
     * @var string
     */
    protected string $fileName;

    public function getContent(): string
    {
        return base64_encode($this->content);
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }
}