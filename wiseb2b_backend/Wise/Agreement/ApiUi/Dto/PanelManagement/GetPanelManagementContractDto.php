<?php

namespace Wise\Agreement\ApiUi\Dto\PanelManagement;

use DateTimeInterface;
use Wise\Agreement\ApiAdmin\Dto\Contract\ContractTranslationDto;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetPanelManagementContractDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Identyfikator umowy',
        example: 1,
    )]
    protected ?int $id = null;

    #[OA\Property(
        description: 'Czy umowa jest aktywna',
        example: true,
    )]
    protected ?bool $isActive;

    #[OA\Property(
        description: 'Stopień wymagalności: 1 - Do korzystania z witryny, 2 - Do złożenia zamówienia, 3 - Dobrowolna',
        example: 1,
    )]
    protected ?int $requirement = null;

    #[OA\Property(
        description: 'Na kogo oddziałowuje umowa: 1 - Klient, 2 - Użytkownik, 3 - Zamówienie',
        example: 1,
    )]
    protected ?int $impact = null;

    #[OA\Property(
        description: 'Kontekst prośby (gdzie ma zostać wyświetlona prośba) ',
        example: 'HOME_PAGE;CHECKOUT',
    )]
    protected ?string $contexts = null;

    #[OA\Property(
        description: 'Symbol - unikalny identyfikator, aby można było odwołać się do konkretnej umowy w kodzie',
        example: 'RULES_2024_11_12',
    )]
    protected ?string $symbol = null;

    #[OA\Property(
        description: 'Typ umowy: RULES - Regulamin, PRIVACY_POLICY - Polityka prywatności, RODO - rodo, NEWSLETTER - Newsletter, MARKETING - Marketing',
        example: 'RULES',
    )]
    protected ?string $type = null;

    #[OA\Property(
        description: 'Role użytkowników, których dotyczy umowa',
        example: 'ROLE_USER_MAIN;ROLE_USER',
    )]
    protected ?string $roles = null;

    #[OA\Property(
        description: 'Status: 1 - W trakcie edycji, 2 - aktywne, 3 - (deprecated) umowa aktywna ale nie można przypisać nowych użytkownik, 4 - nieaktywna',
        example: 1,
    )]
    protected ?int $status = null;

    #[OA\Property(
        description: ' W trakcie edycji',
        example: 1,
    )]
    protected ?string $statusFormatted = null;

    /** @var ContractTranslationDto[] */
    protected ?array $name = null;

    /** @var ContractTranslationDto[] */
    protected ?array $content = null;

    /** @var ContractTranslationDto[] */
    protected ?array $testimony = null;

    #[OA\Property(
        description: 'Regulamin',
        example: 'Regulamin 20.11.2023',
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?string $nameFormatted = null;

    #[OA\Property(
        description: 'Zawartość umowy w formacie HTML',
        example: 'Lorem ipsum',
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?string $contentFormatted = null;

    #[OA\Property(
        description: 'Data obowiązywania umowy od',
        example: '2023-01-01 00:00:01',
    )]
    protected ?DateTimeInterface $fromDate = null;

    #[OA\Property(
        description: 'Data obowiązywania umowy do',
        example: '2028-01-01 00:00:01',
    )]
    protected ?DateTimeInterface $toDate = null;

    #[OA\Property(
        description: 'Data ustawienia umowy na status "deprecated"',
        example: null,
    )]
    protected ?DateTimeInterface $deprecatedDate = null;

    #[OA\Property(
        description: 'Data ustawienia umowy na status "inActive"',
        example: null,
    )]
    protected ?DateTimeInterface $inActiveDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRequirement(): ?int
    {
        return $this->requirement;
    }

    public function setRequirement(?int $requirement): self
    {
        $this->requirement = $requirement;

        return $this;
    }

    public function getImpact(): ?int
    {
        return $this->impact;
    }

    public function setImpact(?int $impact): self
    {
        $this->impact = $impact;

        return $this;
    }

    public function getContexts(): ?string
    {
        return $this->contexts;
    }

    public function setContexts(?string $contexts): self
    {
        $this->contexts = $contexts;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(?string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getName(): ?array
    {
        return $this->name;
    }

    public function setName(?array $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFromDate(): ?DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(?DateTimeInterface $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(?DateTimeInterface $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

    public function getDeprecatedDate(): ?DateTimeInterface
    {
        return $this->deprecatedDate;
    }

    public function setDeprecatedDate(?DateTimeInterface $deprecatedDate): self
    {
        $this->deprecatedDate = $deprecatedDate;

        return $this;
    }

    public function getInActiveDate(): ?DateTimeInterface
    {
        return $this->inActiveDate;
    }

    public function setInActiveDate(?DateTimeInterface $inActiveDate): self
    {
        $this->inActiveDate = $inActiveDate;

        return $this;
    }

    public function getNameFormatted(): ?string
    {
        return $this->nameFormatted;
    }

    public function setNameFormatted(?string $nameFormatted): void
    {
        $this->nameFormatted = $nameFormatted;
    }

    public function getContentFormatted(): ?string
    {
        return $this->contentFormatted;
    }

    public function setContentFormatted(?string $contentFormatted): void
    {
        $this->contentFormatted = $contentFormatted;
    }

    public function isActive(): bool
    {
        return $this->isActive ?? false;
    }

    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getTestimony(): ?array
    {
        return $this->testimony;
    }

    public function setTestimony(?array $testimony): void
    {
        $this->testimony = $testimony;
    }




    public function getStatusFormatted(): ?string
    {
        return $this->statusFormatted;
    }

    public function setStatusFormatted(?string $statusFormatted): self
    {
        $this->statusFormatted = $statusFormatted;

        return $this;
    }

}
