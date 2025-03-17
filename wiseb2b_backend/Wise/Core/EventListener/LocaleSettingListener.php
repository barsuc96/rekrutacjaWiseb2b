<?php

namespace Wise\Core\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;

class LocaleSettingListener
{

    public function __construct(
        public TranslatorInterface $translator,
        private readonly LocaleServiceInterface $localeService,
    ) {
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // Pobiera aktualny locale na podstawie requesta
        $locale = $this->determineLocale($request);

        // Ustawia locale
        $this->translator->setLocale($locale);
        $request->query->set('contentLanguage', $locale);
    }

    private function determineLocale(\Symfony\Component\HttpFoundation\Request $request): string
    {
        return $this->localeService->getCurrentLanguageWithDataFromRequest($request);
    }
}