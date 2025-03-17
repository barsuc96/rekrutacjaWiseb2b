<?php

declare(strict_types=1);

namespace Wise\MultiStore\Service;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\MultiStore\Service\Interfaces\CurrentStoreServiceInterface;
use Wise\MultiStore\Service\Interfaces\LaunchFirstTimeMultiStoreServiceInterface;
use Wise\MultiStore\Service\Store\Interfaces\GetStoreDetailsServiceInterface;
use Wise\MultiStore\WiseMultiStoreExtension;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;
use Wise\User\Service\User\Interfaces\ModifyUserServiceInterface;

/**
 * Serwis do konfiguracji systemu pod MultiStore (pierwszy raz)
 * Serwis jest tworzony w taki sposób, że ponowne uruchomienie komendy nie powinno nic zmieniać w systemie.
 */
class LaunchFirstTimeMultiStoreService implements LaunchFirstTimeMultiStoreServiceInterface
{
    public function __construct(
        private readonly CurrentStoreServiceInterface $currentStoreService,
        private readonly ConfigServiceInterface $configService,
        private readonly ListUsersServiceInterface $listUsersService,
        private readonly ModifyUserServiceInterface $modifyUserService,
        private readonly GetStoreDetailsServiceInterface $getStoreDetailsService,
        private readonly RepositoryManagerInterface $repositoryManager,
    ){}

    public function __invoke(): void
    {
        // Modyfikacja obecnych użytkowników pod multistore
        $this->updateUsers();

    }

    /**
     * Aktualizacja użytkowników
     * - Przypisujemy im identyfikator domyślnego sklepu
     * @return void
     */
    protected function updateUsers(): void
    {
        $defaultStoreSymbol = $this->getDefaultStoreSymbolFromConfiguration();

        // Pobranie identyfikatora domyślnego sklepu
        $params = new CommonDetailsParams();
        $params
            ->setFilters([
                new QueryFilter('symbol', $defaultStoreSymbol)
            ])
            ->setFields([]);

        $defaultStore = ($this->getStoreDetailsService)($params)->read();


        // Pobranie użytkowników, którzy nie mają przypisanego sklepu
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('storeId', null, QueryFilter::COMPARATOR_IS_NULL)
            ])
            ->setFields(['id', 'storeId']);

        $users = ($this->listUsersService)($params)->read();

        if(empty($users)){
            return;
        }

        // Przypisanie użytkownikom domyślnego sklepu
        foreach ($users as $user) {
            $paramsModify = new CommonModifyParams();
            $paramsModify
                ->writeAssociativeArray([
                    'id' => $user['id'],
                    'storeId' => $defaultStore['id']
                ]);

            ($this->modifyUserService)($paramsModify);
            $this->repositoryManager->flush();
        }

    }

    protected function getDefaultStoreSymbolFromConfiguration(): string
    {
        $config = $this->configService->get(WiseMultiStoreExtension::getExtensionAlias());

        return $config['default_store_symbol'];
    }
}
