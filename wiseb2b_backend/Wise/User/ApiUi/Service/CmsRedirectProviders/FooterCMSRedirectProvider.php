<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service\CmsRedirectProviders;

use Wise\Cms\Service\Article\Interfaces\ListArticlesBySectionSymbolServiceInterface;
use Wise\Cms\Service\Article\ListArticlesBySectionSymbolParams;
use Wise\Cms\Service\Cms\CmsRedirectProviderInterface;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;

/**
 * Klasa odpowiedzialna za nadpisywanie kluczy CMS dla stopki dla konkretnego użytkownika
 */
class FooterCMSRedirectProvider implements CmsRedirectProviderInterface
{
    const DEFAULT_FOOTER_ARTICLE_SYMBOL = 'FOOTER';
    const DEFAULT_FOOTER_SECTION_SYMBOL = 'HOME_FOOTER';

    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly ListArticlesBySectionSymbolServiceInterface $listArticlesBySectionSymbolService,
        private readonly LocaleServiceInterface $localeService
    ){}

    /**
     * Sprawdza, czy dany provider obsługuje nadpisywanie przekierowania CMS
     * @param array $redirectCmsList
     * @return bool
     */
    public function supports(array $redirectCmsList): bool
    {
        return true;
    }

    public function __invoke(array &$redirectCmsList): void
    {
        // Pobranie artykułów dla sekcji stopki
        $articles = $this->getArticlesForSectionFooter();

        // Przygotowanie nazwy symbolu artykułu dla danej roli
        $footerSymbolForCurrentRole = static::DEFAULT_FOOTER_ARTICLE_SYMBOL . '_' . $this->getCurrentRole();

        foreach ($articles as $article) {

            // Sprawdzenie, czy artykuł dla danej roli istnieje
            if ($article['symbol'] === $footerSymbolForCurrentRole) {

                // Dodanie informacji o nadpisaniu klucza artykułu do listy nadpisań CMS
                $redirectCmsList[static::DEFAULT_FOOTER_ARTICLE_SYMBOL] = $footerSymbolForCurrentRole;
                break;
            }
        }
    }

    /**
     * Pobiera aktualną rolę użytkownika
     * @return string
     */
    protected function getCurrentRole(): string
    {
        $roles = $this->currentUserService->getRoles();
        $role = current($roles);

        $roleName = str_replace('ROLE_', '', current(UserRoleEnum::getRoleName($role)));
        $roleName = str_replace('USER_MAIN', 'MAIN_USER', $roleName);

        return strtoupper($roleName);
    }

    /**
     * Pobiera artykuły dla sekcji stopki
     * @return array
     */
    protected function getArticlesForSectionFooter(): array
    {
        $params = new ListArticlesBySectionSymbolParams();
        $params
            ->setContentLanguage($this->localeService->getCurrentLanguage())
            ->setSectionSymbol(static::DEFAULT_FOOTER_SECTION_SYMBOL);

        return ($this->listArticlesBySectionSymbolService)($params)->read();
    }
}
