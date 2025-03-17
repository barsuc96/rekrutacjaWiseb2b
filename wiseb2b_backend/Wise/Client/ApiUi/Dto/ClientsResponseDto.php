<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class ClientsResponseDto extends CommonUiApiListResponseDto
{
//    #[OA\Query(
//        description: 'Filtrowanie na podstawie statusu',
//        example: 1,
//    )]
//    protected ?bool $isActive;



    #[OA\Query(
        description: 'Filtrowanie na podstawie statusu',
        example: 1,
    )]
    protected ?int $storyId;

    /** @var ClientResponseDto[] */
    protected ?array $items;

    public function getStoryId(): ?int
    {
        return $this->storyId;
    }

    public function setStoryId(?int $storyId): void
    {
        $this->storyId = $storyId;
    }


}
