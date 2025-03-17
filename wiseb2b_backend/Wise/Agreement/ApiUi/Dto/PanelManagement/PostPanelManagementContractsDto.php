<?php

namespace Wise\Agreement\ApiUi\Dto\PanelManagement;

use DateTimeInterface;
use OpenApi\Attributes as OA;
use Wise\Agreement\ApiAdmin\Dto\Contract\ContractTranslationDto;
use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;

class PostPanelManagementContractsDto extends CommonParametersDto
{
    #[OA\Property(
        description: 'Czy umowa jest aktywna',
        example: true,
    )]
    protected ?bool $isActive;

    #[OA\Property(
        description: 'Stopień wymagalności: 1 - Do korzystania z witryny, 2 - Do złożenia zamówienia, 3 - Dobrowolna',
        example: 1,
    )]
    protected ?int $requirement;

    #[OA\Property(
        description: 'Na kogo oddziałowuje umowa: 1 - Klient, 2 - Użytkownik, 3 - Zamówienie',
        example: 1,
    )]
    protected ?int $impact;

    #[OA\Property(
        description: 'Kontekst prośby (gdzie ma zostać wyświetlona prośba) ',
        example: 'HOME_PAGE;CHECKOUT',
    )]
    protected ?string $contexts;

    #[OA\Property(
        description: 'Symbol - unikalny identyfikator, aby można było odwołać się do konkretnej umowy w kodzie',
        example: 'RULES_2024_11_12',
    )]
    protected ?string $symbol;

    #[OA\Property(
        description: 'Typ umowy: RULES - Regulamin, PRIVACY_POLICY - Polityka prywatności, RODO - rodo, NEWSLETTER - Newsletter, MARKETING - Marketing',
        example: 'RULES',
    )]
    protected ?string $type;

    #[OA\Property(
        description: 'Role użytkowników, których dotyczy umowa',
        example: 'ROLE_USER_MAIN;ROLE_USER',
    )]
    protected ?string $roles;

    #[OA\Property(
        description: 'Status: 1 - W trakcie edycji, 2 - aktywne, 3 - (deprecated) umowa aktywna ale nie można przypisać nowych użytkownik, 4 - nieaktywna',
        example: 1,
    )]
    protected ?int $status;

    /** @var ContractTranslationDto[] */
    protected ?array $name;

    /** @var ContractTranslationDto[] */
    protected ?array $content;

    /** @var ContractTranslationDto[] */
    protected ?array $testimony;

    #[OA\Property(
        description: 'Data obowiązywania umowy od',
        example: '2023-01-01 00:00:01',
    )]
    protected ?DateTimeInterface $fromDate;

    #[OA\Property(
        description: 'Data obowiązywania umowy do',
        example: '2028-01-01 00:00:01',
    )]
    protected ?DateTimeInterface $toDate;

    #[OA\Property(
        description: 'Data ustawienia umowy na status "deprecated"',
        example: null,
    )]
    protected ?DateTimeInterface $deprecatedDate;

    #[OA\Property(
        description: 'Data ustawienia umowy na status "inActive"',
        example: null,
    )]
    protected ?DateTimeInterface $inActiveDate;

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getRequirement(): ?int
    {
        return $this->requirement;
    }

    public function setRequirement(?int $requirement): void
    {
        $this->requirement = $requirement;
    }

    public function getImpact(): ?int
    {
        return $this->impact;
    }

    public function setImpact(?int $impact): void
    {
        $this->impact = $impact;
    }

    public function getContexts(): ?string
    {
        return $this->contexts;
    }

    public function setContexts(?string $contexts): void
    {
        $this->contexts = $contexts;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): void
    {
        $this->symbol = $symbol;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(?string $roles): void
    {
        $this->roles = $roles;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    public function getName(): ?array
    {
        return $this->name;
    }

    public function setName(?array $name): void
    {
        $this->name = $name ?? [];
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): void
    {
        $this->content = $content;
    }

    public function getFromDate(): ?DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(?DateTimeInterface $fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    public function getToDate(): ?DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(?DateTimeInterface $toDate): void
    {
        $this->toDate = $toDate;
    }

    public function getDeprecatedDate(): ?DateTimeInterface
    {
        return $this->deprecatedDate;
    }

    public function setDeprecatedDate(?DateTimeInterface $deprecatedDate): void
    {
        $this->deprecatedDate = $deprecatedDate;
    }

    public function getInActiveDate(): ?DateTimeInterface
    {
        return $this->inActiveDate;
    }

    public function setInActiveDate(?DateTimeInterface $inActiveDate): void
    {
        $this->inActiveDate = $inActiveDate;
    }

    public function getTestimony(): ?array
    {
        return $this->testimony;
    }

    public function setTestimony(?array $testimony): void
    {
        $this->testimony = $testimony;
    }

}
