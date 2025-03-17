<?php

declare(strict_types=1);

namespace Wise\MultiStore\Service\CmsRedirectProviders;

use Wise\Cms\Service\Cms\CmsRedirectProviderInterface;
use Wise\Cms\WiseCmsExtension;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\MultiStore\Service\Interfaces\CurrentStoreServiceInterface;
use Wise\MultiStore\WiseMultiStoreExtension;

/**
 * Klasa odpowiedzialna za nadpisywanie kluczy CMS w wyniku nadpisać kluczy w konfiguracji multistore
 */
class MultiStoreCmsRedirectProvider implements CmsRedirectProviderInterface
{
    public function __construct(
        private readonly CurrentStoreServiceInterface $currentStoreService,
        private readonly ConfigServiceInterface $configService,
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
        // Pobieram aktualny sklep
        $currentStore = $this->currentStoreService->getCurrentStore();

        if ($currentStore !== null) {
            $currentStoreConfig = $this->configService->get(WiseMultiStoreExtension::ALIAS);

            // Sprawdzam, czy istnieje konfiguracja dla sklepu z nadpisaną translacją dla modułu WiseCmsExtension
            if (!empty($currentStoreConfig['translations'][$currentStore->getSymbol()]['modules'][WiseCmsExtension::getExtensionAlias()])){

                // Uzupełniam listę nadpisań CMS o te z modułu MultiStore
                foreach ($currentStoreConfig['translations'][$currentStore->getSymbol()]['modules'][WiseCmsExtension::getExtensionAlias()] as $key => $value) {
                    $redirectCmsList[$key] = $value;
                }
            }
        }
    }
}
