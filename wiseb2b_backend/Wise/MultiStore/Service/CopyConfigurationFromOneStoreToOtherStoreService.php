<?php

declare(strict_types=1);

namespace Wise\MultiStore\Service;


use Wise\MultiStore\Service\Interfaces\CopyConfigurationFromOneStoreToOtherStoreServiceInterface;

/**
 * Serwis do kopiowania konfiguracji z jednego sklepu do drugiego
 */
class CopyConfigurationFromOneStoreToOtherStoreService implements CopyConfigurationFromOneStoreToOtherStoreServiceInterface
{
    protected const SECTION_SYMBOL_MAIN_MENU = 'MAIN_MENU';

    protected string $suffix = '_NEW_STORE';
    protected int $defaultStoreId = 1;
    protected int $newStoreId = 2;
    protected ?int $defaultClientGroupForStore = null;
    protected array $categoriesList = [];

    protected array $sectionsSymbolToCopy = [
        'SUBPAGE',
        'HOME_DESKTOP',
        'HOME_SLIDER',
        'CATEGORIES_IMAGES',
        'CATEGORY_DESCRIPTION',
        'MAIN_MENU',
    ];


    public function __construct() {}

    /**
     * Serwis do kopiowania konfiguracji z jednego sklepu do drugiego
     * @param CopyConfigurationFromOneStoreToOtherStoreParams $params
     * @return void
     * @throws \Exception
     */
    public function __invoke(CopyConfigurationFromOneStoreToOtherStoreParams $params): void
    {
    }
}
