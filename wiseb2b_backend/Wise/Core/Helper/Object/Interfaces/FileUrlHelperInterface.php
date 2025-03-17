<?php

namespace Wise\Core\Helper\Object\Interfaces;

interface FileUrlHelperInterface extends UrlHelperInterface
{
    public function getFileUrl(?string $dir, ?string $storedFilename, bool $removePortFromUrl = false, bool $relative = false): string;
    public function removePortFromUrl($url): string;
}
