<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Object;

use Symfony\Component\HttpFoundation\File\File;

class ApiUploadFileHelper extends File
{
    private string $orgExtension;

    public function __construct($base64Image, bool $detachPrefix = false)
    {
        $filePath = tempnam(sys_get_temp_dir(), 'UploadedFile');

        $file = fopen($filePath, 'wb');

        stream_filter_append($file, 'convert.base64-decode');

        if ($detachPrefix) {
            $base64Image = self::detachPrefix($base64Image);
        }

        fwrite($file, $base64Image);

        $meta_data = stream_get_meta_data($file);

        $path = $meta_data['uri'];

        fclose($file);

        $this->orgExtension = FileHelper::getExtension($path);

        parent::__construct($path, true);
    }

    public static function detachPrefix($base64Image): string
    {
        $base64Image = explode(',', $base64Image);

        return $base64Image[1] ?? $base64Image[0];
    }

    public function getOrgExtension(): string
    {
        return $this->orgExtension;
    }
}
