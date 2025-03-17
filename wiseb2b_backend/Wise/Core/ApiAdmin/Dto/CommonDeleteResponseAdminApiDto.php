<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Dto;

use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * Dto zwracany przez wszystkie metody usuwające obiekty
 * @deprecated  skorzystaj z \Wise\Core\ApiAdmin\Dto\CommonAdminApiDeleteParametersDto
 */
class CommonDeleteResponseAdminApiDto extends CommonResponseDto
{
    #[Ignore]
    protected ?CommonGetAdminApiDto $inputParameters;

    #[Ignore]
    protected ?int $count;

    #[Ignore]
    protected ?string $lastChangeDate;

    #[Ignore]
    protected ?array $objects;
}
