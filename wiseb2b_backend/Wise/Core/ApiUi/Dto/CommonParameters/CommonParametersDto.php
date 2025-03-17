<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto\CommonParameters;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;
use Wise\Core\Dto\AbstractDto;

/**
 * ## Parametry wspólne dla wszystkich endpointów
 * Zadeklarowane poniżej pola są dostępne dla wszystkich endpointów.
 */
class CommonParametersDto extends CommonUiApiDto
{

    /**
     * ## Określenie języka zwracanych danych
     * To właśnie za pomocą tego pola określamy w jakiej wersji językowej chcemy zwrócić dane oraz jaki język jest używany za pomocą TranslationInterface (symfony)
     * Jeśli chcesz pobrać aktualny język w swoim serwisie, skorzystaj z: \Wise\Core\ServiceInterface\Locale\LocaleServiceInterface::getCurrentLanguage()
     * @var string
     */
    #[OA\Parameter(description: 'Wersja językowa', in: 'header', example: 'pl')]
    protected string $contentLanguage;

    public function getContentLanguage(): string
    {
        return $this->contentLanguage;
    }

    public function setContentLanguage(string $contentLanguage): self
    {
        $this->contentLanguage = $contentLanguage;

        return $this;
    }
}
