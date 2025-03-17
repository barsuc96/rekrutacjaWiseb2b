<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Object;

use ReflectionClass;
use \Imagick;

/**
 * Helper do obsługi plików
 */
class FileHelper
{
    public ?string $orgExtension;
    public ?string $baseName;

    protected bool $hasUrlFields = true;
    protected bool $hasBase64Fields = true;

    public function __construct()
    {
        $this->orgExtension = null;
        $this->baseName = null;
    }

    /**
     * Metoda umożliwia upload pliku na podstawie przekazanego base64
     * @param string $fileBase64
     * @param string $path
     * @return string|null
     */
    public function uploadFile(string $fileBase64, string $path, bool $changeToSupportedExtension = false, ?string $fileName = null, ?string $dirFilePath = null): ?string
    {
        $file = new ApiUploadFileHelper($fileBase64, true);

        $this->orgExtension = $file->getOrgExtension();
        $this->baseName = $file->getBasename();
        $fileFileName = null;

        if(!empty($fileName) && !empty($dirFilePath)) {
            $fileName = \Wise\File\Helper\FileHelper::prepareFilename($this->getFileName(), $fileName);
            // Jeśli plik nie istnieje to nazwa ma być taka jak podana (oryginalna)
            if(!file_exists($dirFilePath . $fileName)){
                $fileFileName = $fileName;
            }else{
                $fileExists = true;
                $fileNumber = 1;
                do{
                    $newFileName = $fileNumber . '_' . $fileName;
                    if(!file_exists($dirFilePath . $newFileName)){
                        $fileFileName = $newFileName;
                        $fileExists = false;
                    }
                    $fileNumber++;
                }while($fileExists);
            }
        }

        if(empty($fileFileName)){
            $fileFileName = $this->getFileName();
        }

        $file->move($path, $fileFileName);

        // Jeśli nie obsługujemy formatu, to obsługujemy go ręcznie
        if($changeToSupportedExtension) {
             $newExtension = $this->mapNotSupportedExtension(self::getExtensionFromFilename($fileFileName));
             if(is_string($newExtension)){
                 $imagick = new Imagick($path . $fileFileName);
                 $imagick->setImageFormat($newExtension);
                 $imagick->writeImage(self::renameExtensionInPath($path . $fileFileName, $newExtension));

                 $fileFileName = self::changeExtensionInFilename($fileFileName, $newExtension);
             }
        }

        return $fileFileName;
    }


    /**
     * Metoda umożliwia upload pliku na podstawie przekazanych danych (w $data), jednocześnie aktualizując te dane o informacje o zapisanym pliku do zapisania w encji
     * @param string $className
     * @param array $data - Dane przychodzące do serwisów Add lub Modify
     * @param string $dirPath - Ścieżka do katalogu, w którym ma być zapisany plik
     * @return void
     * @throws \ReflectionException
     */
    public function uploadFileByData(string $className, array &$data, string $dirPath): void
    {
        $base64CurrentFile = null;

        // Pobieramy plik w formacie base64
        if (array_key_exists('base64', $data)) {
            $base64CurrentFile =  $data['base64'] ?? null;
            unset($data['base64']);
        }

        // Jeśli nie ma pliku to kończymy działanie metody
        if($base64CurrentFile == null) {
            return;
        }

        // Upload pliku
        $filename = $this->uploadFile($base64CurrentFile, $dirPath);

        // Aktualizacja danych o pliku
        $reflectionClass = new ReflectionClass($className);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            if ($property->getName() == 'storedFilename') {
                $data[$property->getName()] = $filename;
            }

            if ($property->getName() == 'extension') {
                $data[$property->getName()] = $this->orgExtension;
            }

            if ($property->getName() == 'dir') {
                $data[$property->getName()] = $dirPath;
            }
        }
    }

    /**
     * Metoda umożliwia usunięcie pliku
     * @param string $path
     * @return bool
     */
    public function removeFile($path): bool
    {
        return file_exists($path) && unlink($path);
    }

    /**
     * Metoda umożliwia pobranie rozszerzenia pliku na podstawie przekazanego mime type
     * @param string $mimeType
     * @return string
     */
    public static function getExtensionFromMimeType($mimeType): string
    {
        $extensions = array(
            'image/jpeg' => 'jpeg',
            'text/csv' => 'csv',
            'application/msword' => 'doc',
            'image/gif' => 'gif',
            'image/x-icon' => 'ico',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
            'text/plain' => 'txt',
            'video/mp4' => 'mp4',
            'video/quicktime' => 'mov',
            'image/webp' => 'webp',
            'image/tiff' => 'tif',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls', // Pliki Excel (.xls)
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx', // Pliki Excel (.xlsx)
            'application/vnd.ms-powerpoint' => 'ppt', // Prezentacje PowerPoint (.ppt)
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx', // Prezentacje PowerPoint (.pptx)
            'application/rtf' => 'rtf', // Rich Text Format (.rtf)
            'application/zip' => 'zip', // Archiwa ZIP (.zip)
            'application/x-rar-compressed' => 'rar', // Archiwa RAR (.rar)
            'application/x-7z-compressed' => '7z', // Archiwa 7z (.7z)
            'application/x-tar' => 'tar', // Archiwa Tar (.tar)
            'application/gzip' => 'gz', // Archiwa Gzip (.gz)
            'application/json' => 'json', // JSON (.json)
            'application/xml' => 'xml', // XML (.xml)
            'text/html' => 'html', // HTML (.html)
            'application/javascript' => 'js', // JavaScript (.js)
            'text/css' => 'css', // CSS (.css)
            'audio/mpeg' => 'mp3', // Pliki audio MP3 (.mp3)
            'audio/ogg' => 'ogg', // Pliki audio Ogg (.ogg)
            'audio/wav' => 'wav', // Pliki audio WAV (.wav)
            'video/x-msvideo' => 'avi', // Pliki wideo AVI (.avi)
            'video/x-matroska' => 'mkv', // Pliki wideo Matroska (.mkv)
            'image/bmp' => 'bmp', // Obrazy BMP (.bmp)
            'image/svg+xml' => 'svg', // Obrazy SVG (.svg)
            'image/heif' => 'heif', // Obrazy HEIF (.heif)
            'image/heic' => 'heic', // Obrazy HEIC (.heic)
        );

        return $extensions[$mimeType] ?? "txt";
    }

    /**
     * Metoda umożliwia pobranie rozszerzenia pliku obsługiwanego na podstawie przekazanego rozszerzenia
     * @param string $extension
     * @return string|bool
     * @example ResizeService nie obsługuje plików tif, więc musimy przekonwertować na inny typ zdjęcia
     */
    public function mapNotSupportedExtension(string $extension): string|bool
    {
        $extensions = array(
            'tif' => 'jpg',
        );

        return $extensions[$extension] ?? false;
    }

    /**
     * Zwraca rozszerzenia plików, które są zdjęciami
     * @return array
     */
    public static function imageExtensions(): array
    {
        return ['jpg', 'jpeg', 'png', 'tif'];
    }

    /**
     * Metoda umożliwia pobranie mime type pliku
     * @param string $filePath
     * @return bool|string
     */
    public static function getMimeType($filePath): bool|string
    {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $filePath);
        finfo_close($fileInfo);

        return $mimeType;
    }

    /**
     * Metoda umożliwia pobranie rozszerzenia pliku na podstawie przekazanego url
     * @param string $url
     * @return string
     */
    public static function getExtension($filePath): string
    {
        return static::getExtensionFromMimeType(static::getMimeType($filePath));
    }

    /**
     * Metoda umożliwia pobranie mime type pliku na podstawie przekazanego base64
     * @param string $base64
     * @return string
     */
    public static function getMimeTypeBase64($base64): string
    {
        return substr($base64, 5, strpos($base64, ';') - 5);
    }

    /**
     * Metoda przygotowuje nazwę pliku
     * @param string $base64
     * @return string
     */
    protected function getFileName(): string
    {
        $fileName = date('Y_m_d_H_i_s');
        $fileName .= '_' . md5($this->baseName . $fileName);
        $fileName .= '.' . $this->orgExtension;

        return $fileName;
    }

    /**
     * Przygotowuje base64 z pliku na podstawie przekazanego url
     * @param string|null $url
     * @return string|null - base64 file
     */
    public function prepareBase64FromFileUrl(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }

        try{
            $file = file_get_contents($url);
        }catch (\Exception $e) {
            return null;
        }

        return base64_encode($file);
    }


    /**
     * Metoda pozwala zmienić rozszerzenie pliku w ścieżce
     * @example "/var/www/backend/public/files/images/2024_04_09_05_44_57_f5916be29b08f9f3edf6e9c5ea9ef288.tif" -> "/var/www/backend/public/files/images/2024_04_09_05_44_57_f5916be29b08f9f3edf6e9c5ea9ef288.jpg"
     * @param string $path
     * @param $newExtension
     * @return string|null
     */
    public static function renameExtensionInPath(string $path, $newExtension): ?string
    {
        $newName = preg_replace('/\.[^.]+$/', '.' . $newExtension, $path);
        if (rename($path, $newName)) {
            return $newName;
        }

        return null;
    }

    /**
     * Metoda pozwala zmienić rozszerzenie pliku w nazwie
     * @example "2024_04_09_05_44_57_f5916be29b08f9f3edf6e9c5ea9ef288.tif" -> "2024_04_09_05_44_57_f5916be29b08f9f3edf6e9c5ea9ef288.jpg"
     * @param $filename
     * @param $newExtension
     * @return string
     */
    public static function changeExtensionInFilename($filename, $newExtension): string {
        $parts = pathinfo($filename);

        return $parts['filename'] . '.' . $newExtension;
    }

    /**
     * Metoda pozwala pobrać rozszerzenie pliku z nazwy
     * @param $filename
     * @return mixed
     */
    public static function getExtensionFromFilename($filename): string {
        $parts = pathinfo($filename);

        return $parts['extension'];
    }

    /**
     * Weryfikuje czu string jest w formacie base64
     * @param string $string
     * @return bool
     */
    public static function isBase64Format(string $string): bool
    {
        return base64_encode(base64_decode($string)) === $string;
    }

    /**
     * Zwraca rozszerzenie pliku w formacie base64
     * @param string $base64
     * @return string
     */
    public static function getExtensionFromFileBase64(string $base64): string
    {
        return explode('/', mime_content_type($base64))[1];
    }

    /**
     * Sprawdza, czy rozszerzenie pliku w formacie base64 jest poprawne
     * @param string $base64
     * @param string $extension
     * @return bool
     */
    public static function isValidExtensionInFileBase64(string $base64, string $extension): bool
    {
        return self::getExtensionFromFileBase64($base64) === $extension;
    }




    public function isHasUrlFields(): bool
    {
        return $this->hasUrlFields;
    }

    public function setHasUrlFields(bool $hasUrlFields): self
    {
        $this->hasUrlFields = $hasUrlFields;

        return $this;
    }

    public function isHasBase64Fields(): bool
    {
        return $this->hasBase64Fields;
    }

    public function setHasBase64Fields(bool $hasBase64Fields): self
    {
        $this->hasBase64Fields = $hasBase64Fields;

        return $this;
    }
}
