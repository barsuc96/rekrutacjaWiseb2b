<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Dto;

use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * Dto zwracany przez wszystkie metody dodające/modyfikujące obiekty
 */
class CommonPutResponseAdminApiDto extends CommonResponseDto
{
    #[Ignore]
    protected ?CommonGetAdminApiDto $inputParameters = null;

    #[Ignore]
    protected ?int $count = null;

    #[Ignore]
    protected ?string $lastChangeDate = null;
}
