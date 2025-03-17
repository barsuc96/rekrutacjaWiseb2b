<?php

namespace Wise\Core\Helper\Object\Interfaces;

interface UrlHelperInterface
{
    public function removePortFromUrl($url): string;
    public function prependBaseUrl(string $suffix, bool $removePortFromUrl = false): string;
    public function changeExtensionForFileInUrl(string $url, string $newExtension): string;
    public function prepareUrlWithQueryElements(string $url, array $queryGetElements): string;
    public function getRelativeUrl(?string $url): ?string;
}
