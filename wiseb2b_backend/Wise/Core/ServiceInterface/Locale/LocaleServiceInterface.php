<?php

declare(strict_types=1);

namespace Wise\Core\ServiceInterface\Locale;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

interface LocaleServiceInterface
{
    /**
     * Zwraca aktualny język
     * @return string|null
     */
    public function getCurrentLanguage(): ?string;

    /**
     * Zwraca język na podstawie informacji z przekazanego Requesta
     * @param Request $request
     * @return string|null
     */
    public function getCurrentLanguageWithDataFromRequest(Request $request): ?string;

    /**
     * Ustawia język dla komponentu translatora
     * @param TranslatorInterface $translator
     * @return void
     */
    public function reconfigureLocaleTranslationForCurrentLanguage(TranslatorInterface $translator): void;
}
