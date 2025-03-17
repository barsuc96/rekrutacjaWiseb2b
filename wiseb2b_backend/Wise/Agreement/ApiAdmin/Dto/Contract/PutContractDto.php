<?php

namespace Wise\Agreement\ApiAdmin\Dto\Contract;

use DateTimeInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\ApiAdmin\Dto\AbstractSingleObjectAdminApiRequestDto;

class PutContractDto extends AbstractSingleObjectAdminApiRequestDto
{
    #[OA\Property(
        description: 'Id umowy identyfikujące klienta w ERP',
        example: 'CONTRACT-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Id umowy, może mieć maksymalnie {{ limit }} znaków',
    )]
    #[FieldEntityMapping('idExternal')]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    #[FieldEntityMapping('id')]
    protected int $internalId;

    #[OA\Property(
        description: 'Stopień wymagalności: 1 - Do korzystania z witryny, 2 - Do złożenia zamówienia, 3 - Dobrowolna',
        example: 1,
    )]
    protected int $requirement;

    #[OA\Property(
        description: 'Na kogo oddziałowuje umowa: 1 - Klient, 2 - Użytkownik, 3 - Zamówienie',
        example: 1,
    )]
    protected int $impact;

    #[OA\Property(
        description: 'Kontekst prośby (gdzie ma zostać wyświetlona prośba) ',
        example: 'HOME_PAGE;CHECKOUT',
    )]
    protected string $contexts;

    #[OA\Property(
        description: 'Symbol - unikalny identyfikator, aby można było odwołać się do konkretnej umowy w kodzie',
        example: 'RULES_2024_11_12',
    )]
    protected string $symbol;

    #[OA\Property(
        description: 'Typ umowy: RULES - Regulamin, PRIVACY_POLICY - Polityka prywatności, RODO - rodo, NEWSLETTER - Newsletter, MARKETING - Marketing',
        example: 'RULES',
    )]
    protected string $type;

    #[OA\Property(
        description: 'Role użytkowników, których dotyczy umowa',
        example: 'ROLE_USER_MAIN;ROLE_USER',
    )]
    protected string $roles;

    #[OA\Property(
        description: 'Status: 1 - W trakcie edycji, 2 - aktywne, 3 - (deprecated) umowa aktywna ale nie można przypisać nowych użytkownik, 4 - nieaktywna',
        example: 1,
    )]
    protected int $status;

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

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInternalId(): int
    {
        return $this->internalId;
    }

    public function setInternalId(int $internalId): self
    {
        $this->internalId = $internalId;

        return $this;
    }

    public function getRequirement(): int
    {
        return $this->requirement;
    }

    public function setRequirement(int $requirement): self
    {
        $this->requirement = $requirement;

        return $this;
    }

    public function getImpact(): int
    {
        return $this->impact;
    }

    public function setImpact(int $impact): self
    {
        $this->impact = $impact;

        return $this;
    }

    public function getContexts(): string
    {
        return $this->contexts;
    }

    public function setContexts(string $contexts): self
    {
        $this->contexts = $contexts;

        return $this;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRoles(): string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
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

    public function getTestimony(): ?array
    {
        return $this->testimony;
    }

    public function setTestimony(?array $testimony): self
    {
        $this->testimony = $testimony;

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
}
