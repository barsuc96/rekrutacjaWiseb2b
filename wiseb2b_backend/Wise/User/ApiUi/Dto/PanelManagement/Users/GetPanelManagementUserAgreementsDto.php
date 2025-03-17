<?php

namespace Wise\User\ApiUi\Dto\PanelManagement\Users;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementUserAgreementsDto extends CommonUiApiListResponseDto
{
    #[OA\Path(
        description: 'Identyfikator użytkownika',
        example: 1,
    )]
    protected int $userId;

    /** @var GetPanelManagementUserAgreementDto[] */
    protected ?array $items;

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(?array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }


}
