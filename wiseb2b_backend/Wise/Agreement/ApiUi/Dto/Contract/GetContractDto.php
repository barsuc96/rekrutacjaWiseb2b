<?php

namespace Wise\Agreement\ApiUi\Dto\Contract;

use DateTimeInterface;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetContractDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected ?int $id = null;

    #[OA\Property(
        description: 'Nazwa umowy',
        example: 'Regulamin',
    )]
    protected ?string $name = null;

    #[OA\Property(
        description: 'Treść umowy',
        example: 'Lorem ipsum...',
    )]
    protected ?string $content = null;

    #[OA\Property(
        description: 'Treść oświadczenia umowy',
        example: 'Wyrażenie zgody na newsletter',
    )]
    protected ?string $testimony = null;

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
        description: 'IP z którego wyrażono zgodę',
        example: '4.321.45.213',
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?string $agreeIp = null;


    #[OA\Property(
        description: 'Data akceptacji zgody',
        example: '2023-01-01 00:00:01',
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?DateTimeInterface $agreeDate = null;

    #[OA\Property(
        description: 'IP z którego zrezygnowano ze zgody',
        example: '4.321.45.213',
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?string $disagreeIp = null;

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

    #[OA\Property(
        description: 'Czy na froncie musi wymusić zgodę aby przejść dalej',
        example: false,
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?bool $userMustAccept = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
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

    public function getAgreeIp(): ?string
    {
        return $this->agreeIp;
    }

    public function setAgreeIp(?string $agreeIp): void
    {
        $this->agreeIp = $agreeIp;
    }

    public function getAgreeDate(): ?DateTimeInterface
    {
        return $this->agreeDate;
    }

    public function setAgreeDate(?DateTimeInterface $agreeDate): void
    {
        $this->agreeDate = $agreeDate;
    }

    public function getDisagreeIp(): ?string
    {
        return $this->disagreeIp;
    }

    public function setDisagreeIp(?string $disagreeIp): void
    {
        $this->disagreeIp = $disagreeIp;
    }

    public function getDisagreeDate(): ?DateTimeInterface
    {
        return $this->disagreeDate;
    }

    public function setDisagreeDate(?DateTimeInterface $disagreeDate): void
    {
        $this->disagreeDate = $disagreeDate;
    }

    public function getHasActiveAgree(): ?bool
    {
        return $this->hasActiveAgree;
    }

    public function setHasActiveAgree(?bool $hasActiveAgree): void
    {
        $this->hasActiveAgree = $hasActiveAgree;
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


    public function getUserMustAccept(): ?bool
    {
        return $this->userMustAccept;
    }

    public function setUserMustAccept(?bool $userMustAccept): self
    {
        $this->userMustAccept = $userMustAccept;

        return $this;
    }

}
