<?php

namespace Wise\User\Service\Terms;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\User\Service\Terms\Interfaces\ListTermsServiceInterfaces;

/**
 * Serwis zwraca listę regulaminów (artykułów, których sekcja ma symbol USER_REGULATIONS)
 */
class ListTermsService implements ListTermsServiceInterfaces
{
    public function __construct(
        protected readonly ContainerBagInterface $configParams,
        protected readonly TranslationService $translationService,
        protected readonly LocaleServiceInterface $localeService
    )
    {
    }

    public function __invoke(CommonListParams $params): CommonServiceDTO
    {
        // Zwracamy dane
        $serviceDto = new CommonServiceDTO();
        $serviceDto->writeAssociativeArray([]);

        return $serviceDto;
    }
}
