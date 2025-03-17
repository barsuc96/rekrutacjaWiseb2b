<?php

namespace Wise\User\ApiUi\Dto\Contract;

use DateTimeInterface;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetUserContractDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected ?int $id = null;

    #[OA\Property(
        description: 'Treść oświadczenia umowy',
        example: 'Wyrażenie zgody na newsletter',
    )]
    protected ?string $testimony = null;

    #[OA\Property(
        description: 'Treść umowy',
        example: 'Lorem ipsum...',
    )]
    protected ?string $content = null;

    #[OA\Property(
        description: 'Data akceptacji zgody',
        example: '2023-01-01 00:00:01',
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?DateTimeInterface $agreeDate = null;

    #[OA\Property(
        description: 'Data rezygnacji ze zgody',
        example: '2023-01-01 00:00:01',
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?DateTimeInterface $disagreeDate = null;

    #[OA\Property(
        description: 'Czy posiada aktywną akceptację (czyli sytuacje gdzie użytkownik zatwierdził zgodę i dodatkowo jest ona aktywna)',
        example: false,
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?bool $hasActiveAgree = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTestimony(): ?string
    {
        return $this->testimony;
    }

    public function setTestimony(?string $testimony): self
    {
        $this->testimony = $testimony;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAgreeDate(): ?DateTimeInterface
    {
        return $this->agreeDate;
    }

    public function setAgreeDate(?DateTimeInterface $agreeDate): self
    {
        $this->agreeDate = $agreeDate;

        return $this;
    }

    public function getDisagreeDate(): ?DateTimeInterface
    {
        return $this->disagreeDate;
    }

    public function setDisagreeDate(?DateTimeInterface $disagreeDate): self
    {
        $this->disagreeDate = $disagreeDate;

        return $this;
    }

    public function getHasActiveAgree(): ?bool
    {
        return $this->hasActiveAgree;
    }

    public function setHasActiveAgree(?bool $hasActiveAgree): self
    {
        $this->hasActiveAgree = $hasActiveAgree;

        return $this;
    }


}
