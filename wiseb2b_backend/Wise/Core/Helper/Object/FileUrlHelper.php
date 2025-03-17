<?php

namespace Wise\Core\Helper\Object;

use Symfony\Component\HttpFoundation\RequestStack;
use Wise\Core\Helper\Object\Interfaces\FileUrlHelperInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;

class FileUrlHelper extends UrlHelper implements FileUrlHelperInterface
{
    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly ConfigServiceInterface $configService,
        private readonly RequestStack $requestStack
    ) {
        parent::__construct($kernel, $configService, $requestStack);
    }

    /**
     * Zwraca URL do pliku
     * @param string|null $dir
     * @param string|null $storedFilename
     * @param bool $removePortFromUrl
     * @param bool $relative
     * @return string
     */
    public function getFileUrl(?string $dir, ?string $storedFilename, bool $removePortFromUrl = false, bool $relative = false): string
    {
        if(!$dir || !$storedFilename){
            return '';
        }

        // Przygotowanie prefix URL (ścieżka do pliku na podstawie katalogu, w którym został zapisany plik)
        $prefixUrl = $this->preparePrefix($dir);

        // Połączenie prefix URL z nazwą pliku
        $url = $prefixUrl . $storedFilename;

        // Usuwanie portu z URL-a
        if($this->canRemovePortFromUrl($removePortFromUrl)){
            return $this->removePortFromUrl($url);
        }

        // Zwracanie URL-a relatywnego
        if($relative){
            return $this->getRelativeUrl($url);
        }

        return $url;
    }

    /**
     * Przygotowuje prefix URL
     * @param string $dir
     * @return string|null
     */
    protected function preparePrefix(string $dir): ?string
    {
        $prefixUrl = null;
        if(str_contains($dir, 'cart')){
            $prefixUrl = $this->configService->get('carts_file_path_api');
        }else if(str_contains($dir, 'cms')){
            $prefixUrl = $this->configService->get('cms_media_file_url');
        }else if(str_contains($dir, 'article_field')){
            $prefixUrl = $this->configService->get('article_field_file_url');
        }else if(str_contains($dir, 'tmp')){
            $prefixUrl = $this->configService->get('tmp_file_path_api');
        }else if(str_contains($dir, 'images')){
            $prefixUrl = $this->configService->get('images_file_path_api');
        }else if(str_contains($dir, 'product')){
            $prefixUrl = $this->configService->get('products_file_path_url');
        }else if(str_contains($dir, 'document')){
            $prefixUrl = $this->configService->get('document_file_url');
        }else if(str_contains($dir, 'order')){
            $prefixUrl = $this->configService->get('orders_file_path_api');
        }else if(str_contains($dir, 'icon')){
            $prefixUrl = $this->configService->get('icons_file_path_api');
        }else if(str_contains($dir, 'categories')){
            $prefixUrl = $this->configService->get('categories_file_url');
        }else if(str_contains($dir, 'store_files')){
            $prefixUrl = $this->configService->get('store_file_url');
        }

        return $prefixUrl;
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
}
