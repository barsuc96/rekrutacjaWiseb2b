<?php

namespace Wise\Core\Service;

use Elephox\Mimey\MimeTypes;
use Wise\Core\Enum\FileType;

class MimeService
{
    public function checkFileType($filename): array
    {
        $mimeTypes = new MimeTypes();
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $mimeType = $mimeTypes->getMimeType($extension);

        $isImage = strpos($mimeType, 'image') === 0;
        $isVideo = strpos($mimeType, 'video') === 0;

        if ($isImage) {
            $fileType = FileType::IMAGE;
        } elseif ($isVideo) {
            $fileType = FileType::VIDEO;
        } else {
            $fileType = FileType::OTHER;
        }

        return [
            'type' => $fileType,
            'extension' => $extension
        ];
    }
}
