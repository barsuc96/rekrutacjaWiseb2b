<?php

namespace Wise\Core\Dto;

/**
 *  Klasa DTO, obsługująca parametry związane z plikami
 *  Służy do przenoszenia informacji z serwisu API do serwisu Aplikacji
 */
class CommonFileServiceDTO extends CommonServiceDTO
{
    /**
     * Zawiera informacje w formie string o zawartości pliku
     * @var string
     */
    protected string $content;

    /**
     * Nazwa pliku wraz z rozszerzeniem
     * @var string
     */
    protected string $fileName;

    /**
     * Typ pliku (xml, pdf, xls, csv)
     * @var string|null
     */
    protected ?string $fileType;

    /**
     * Język
     * @var string|null
     */
    protected ?string $contentLanguage;

    public function getContent(): string
    {
        return $this->content;
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

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function setFileType(?string $fileType): void
    {
        $this->fileType = $fileType;
    }

    public function getContentLanguage(): ?string
    {
        return $this->contentLanguage;
    }

    public function setContentLanguage(?string $contentLanguage): void
    {
        $this->contentLanguage = $contentLanguage;
    }


}