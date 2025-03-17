<?php

declare(strict_types=1);

namespace Wise\I18n\Service\Layout;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\I18n\ApiUi\Dto\Layout\LayoutLanguagesResponseDto;
use Wise\I18n\Service\Layout\Interfaces\ListLanguagesServiceInterface;
use Wise\I18n\WiseI18nExtension;
use Wise\MultiStore\Service\Interfaces\TranslateValueByCurrentStoreServiceInterface;

class ListLanguagesService implements ListLanguagesServiceInterface
{
    public function __construct(
        private readonly string $languages,
        private readonly CommonDataTransformer $commonDataTransformer,
        private readonly TranslatorInterface $translator,
        private readonly TranslateValueByCurrentStoreServiceInterface $translateValueByCurrentStoreService
    ) {}

    public function __invoke(): CommonServiceDTO
    {
        $titleWebsiteKey = ($this->translateValueByCurrentStoreService)(moduleName: WiseI18nExtension::ALIAS, key: 'title_website');
        $descriptionWebsiteKey = ($this->translateValueByCurrentStoreService)(moduleName: WiseI18nExtension::ALIAS, key: 'description_website');

        $languages = json_decode($this->languages, true);
        $languagesData = array_map(function (string $id, string $name) use ($titleWebsiteKey, $descriptionWebsiteKey) {
            return $this->commonDataTransformer::transformFromArray(
                [
                    'id' => $id,
                    'name' => $name,
                    'titleWebsite' => $this->translator->trans($titleWebsiteKey ,locale: $id),
                    'descriptionWebsite' => $this->translator->trans($descriptionWebsiteKey ,locale: $id),
                ],
                LayoutLanguagesResponseDto::class,
            );
        }, array_keys($languages), array_values($languages));

        ($resultDto = new CommonServiceDTO())->writeAssociativeArray($languagesData);

        return $resultDto;
    }
}
