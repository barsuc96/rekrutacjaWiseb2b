<?php

declare(strict_types=1);

namespace Wise\Core\Locale;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;

/**
 * Klasu służąca do pobrania, aktualnego ustawienia local,
 * czyli jaki mamy ustawiony język
 */
class LocaleService implements LocaleServiceInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ConfigServiceInterface $configService
    ) {}

    /**
     * Pobieramy aktualny język, domyślnie ustawiony na pl
     */
    public function getCurrentLanguage(): ?string
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if ($currentRequest) {
            return $this->getCurrentLanguageWithDataFromRequest($currentRequest);
        }

        return $this->configService->get('defaultContentLanguage');
    }

    /**
     * Zwraca język na podstawie informacji z przekazanego Requesta
     * @param Request $request
     * @return string|null
     */
    public function getCurrentLanguageWithDataFromRequest(Request $request): ?string
    {
        return strtolower($this->getCurrentLanguageFromHeaders($request) ?? $this->configService->get('defaultContentLanguage'));
    }

    /**
     * Ustawia język dla translatora
     * @return void
     */
    public function reconfigureLocaleTranslationForCurrentLanguage(TranslatorInterface $translator): void
    {
        $translator->setLocale($this->getCurrentLanguage());
    }

    /**
     * Pobiera język z nagłówka request'u
     * @param Request $request
     * @return string|null
     */
    private function getCurrentLanguageFromHeaders(Request $request): ?string
    {
        return $request->headers->get('Content-Language');
    }
}
