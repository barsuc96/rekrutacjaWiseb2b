<?php
namespace Wise\Core\Tests\Support\Trait;

trait FileTesterUtilsTrait
{
    public function getImageBase64(): array
    {
        return [
            'data' => 'iVBORw0KGgoAAAANSUhEUgAAAAIAAAACAQMAAABIeJ9nAAAABlBMVEUAAAADAwMVBQXvAAAADElEQVQI12NoYGAAAAGEAIEo3XrXAAAAAElFTkSuQmCC',
            'extension' => 'png'
        ];
    }

    public function getVideoBase64(): array
    {
        $path = __DIR__ . '/../Data/test_video.mp4';
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return [
            'data' => 'data:image/' . $extension . ';base64,' . base64_encode($data),
            'extension' => $extension
        ];
    }
}
