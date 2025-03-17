<?php

namespace Wise\Core\Helper\Object;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Wise\Core\Helper\Object\Interfaces\UrlHelperInterface;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;

/**
 * Helper udostępniający metody do obsługi URL
 */
class UrlHelper implements UrlHelperInterface
{
    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly ConfigServiceInterface $configService,
        private readonly RequestStack $requestStack
    ){}

    /**
     * Usuwa port z URL-a
     * @param $url
     * @return string
     */
    public function removePortFromUrl($url): string
    {
        $parsedUrl = parse_url($url);

        $urlWithoutPort = '';

        if (isset($parsedUrl['scheme'])) {
            $urlWithoutPort .= $parsedUrl['scheme'] . '://';
        }
        if (isset($parsedUrl['host'])) {
            $urlWithoutPort .= $parsedUrl['host'];
        }

        // Dodaje ścieżkę i zapytanie, jeśli istnieją
        if (!empty($parsedUrl['path'])) {
            $urlWithoutPort .= $parsedUrl['path'];
        }
        if (!empty($parsedUrl['query'])) {
            $urlWithoutPort .= '?' . $parsedUrl['query'];
        }

        return $urlWithoutPort;
    }

    /**
     * Dodaje bazowy URL do podanego URL-a (suffix). Obsługuje URL-e produkcyjne i deweloperskie
     * @param string|null $suffix - sufiks URL-a. np. "/api/orders"
     * @param bool $removePortFromUrl
     * @return string
     */
    public function prependBaseUrl(?string $suffix = null, bool $removePortFromUrl = false): string
    {
        $baseUrl = $this->configService->get('app_channel') . "://" . $this->configService->get('api_trusted_host') . ":" . $this->configService->get('web_port');

        if($this->canRemovePortFromUrl($removePortFromUrl)){
            $baseUrl = $this->removePortFromUrl($baseUrl);
        }

        if($suffix == null){
            return $baseUrl;
        }

        return $baseUrl . $suffix;
    }

    /**
     * Zmienia rozszerzenie pliku w URL-u
     * @param string $url
     * @param string $newExtension - example: 'jpg'
     * @return string
     */
    public function changeExtensionForFileInUrl(string $url, string $newExtension): string
    {
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        return substr($url, 0, -(strlen($extension) + 1)) . '.' . $newExtension;
    }

    /**
     * Pozwala stworzyć url dodając wszystkie elementy z $queryGetElements do query jako parametry GET
     * @param string $url - np. 'https://example.com/thanks'
     * @param array $queryGetElements np. ['token' => 'xyz']
     * @return string - 'https://example.com/thanks?token=xyz
     */
    public function prepareUrlWithQueryElements(string $url, array $queryGetElements): string
    {
        // Sprawdzamy, czy URL już zawiera znak zapytania
        $separator = (parse_url($url, PHP_URL_QUERY) == null) ? '?' : '&';

        // Generowanie parametrów GET
        $queryString = http_build_query($queryGetElements);

        return $url . $separator . $queryString;
    }

    /**
     * Sprawdza, czy można usunąć port z URL-a
     * @param bool $removePortFromUrl
     * @return bool
     */
    public function canRemovePortFromUrl(bool $removePortFromUrl = false): bool
    {
        return $removePortFromUrl  || $this->isHttps() || $this->configService->get('remove_port_from_url') == 'true' || $this->kernel->getEnvironment() == 'prod';
    }

    /**
     * Czy obecna strona jest szyfrowana przez HTTPS
     * Zakładamy, że wersje HTTPS są wersjami zainstalowanymi na serwerze, zaś HTTP są uruchamiane lokalnie
     * @return bool
     */
    public function isHttps(): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        if($request === null){
            return true;
        }

        return $request->isSecure();
    }

    /**
     * Zwraca url relatywny
     * @param string|null $url
     * @return string|null
     */
    public function getRelativeUrl(?string $url): ?string
    {
        if(empty($url)){
            return $url;
        }

        $parsedUrl = parse_url($url);

        // Jeśli nie ma ścieżki w URL, zwróć pusty string
        if (!isset($parsedUrl['path'])) {
            return '';
        }

        // Zwróć tylko ścieżkę, pomijając protokół, host i inne komponenty
        return $parsedUrl['path'];
    }
}
