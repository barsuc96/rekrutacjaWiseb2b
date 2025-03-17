<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Validator\Constraints as WiseAssert;

class PostUserRegisterRequestDto extends AbstractDto
{
    #[OA\Property(
        description: 'Pozwala określić etap rejestracji (jeśli proces jest podzielony na kilka etapów)',
        example: 'FULL',
    )]
    protected ?string $processFlags;

    #[OA\Property(
        description: 'Czy proces ma służyć tylko do walidacji danych? (jeśli wartość będzie "true" to nie zostanie zapisany nowy użytkownik a tylko zwalidowany)',
        example: false,
    )]
    protected bool $processOnlyCheck;

    #[OA\Property(
        description: 'Typ użytkownika (COMPANY lub INDIVIDUAL)',
        example: 'COMPANY|INDIVIDUAL',
    )]
    protected ?string $type;

    #[OA\Property(
        description: 'Imię użytkownika',
        example: 'Jan',
    )]
    #[WiseAssert\NotBlank]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko użytkownika',
        example: 'Nowak',
    )]
    #[WiseAssert\NotBlank]
    protected ?string $lastName;

    #[OA\Property(
        description: 'Nazwa odbiorcy/firmy',
        example: 'WiseB2B Sp. z o.o.',
    )]
    protected ?string $companyName;

    #[OA\Property(
        description: 'Imię użytkownika',
        example: '1231231231',
    )]
    protected ?string $taxNumber;

    #[OA\Property(
        description: 'Numer telefonu',
        example: '123456789',
    )]
    #[WiseAssert\NotBlank]
    protected ?string $phone;

    #[OA\Property(
        description: 'Adres email',
        example: 'user-email@example.com',
    )]
    #[WiseAssert\NotBlank]
    protected ?string $email;

    #[OA\Property(
        description: 'Hasło',
        example: 'cCjmXrbI8ZZ6rC7',
    )]
    protected ?string $password;

    #[OA\Property(
        description: 'Potwierdzenie maila',
        example: 'cCjmXrbI8ZZ6rC7',
    )]
    protected ?string $passwordConfirm;

    #[OA\Property(
        description: 'Strona kontaktowa',
        example: 'www.wiseb2b.eu',
    )]
    protected ?string $website;

    #[OA\Property(
        description: 'Token recaptcha',
        example: 'VXcnujfsASdsvjniaxvdf234bdfgdf11g',
    )]
    protected ?string $recaptchaToken;

    #[Assert\Valid]
    protected ?RegisterAddressDto $billingAddress;

    #[OA\Property(
        description: 'Imię użytkownika - odbiorcy',
        example: 'Jan',
    )]
    protected ?string $receiverFirstName;

    #[OA\Property(
        description: 'Nazwisko użytkownika - odbiorcy',
        example: 'Nowak',
    )]
    protected ?string $receiverLastName;

    #[Assert\Valid]
    protected ?RegisterAddressDto $receiverAddress;

    /** @var PostUserRegisterAgreementsRequestDto[] */
    protected array $agreements;

    public function getProcessFlags(): ?string
    {
        return $this->processFlags;
    }

    public function setProcessFlags(?string $processFlags): self
    {
        $this->processFlags = $processFlags;

        return $this;
    }

    public function isProcessOnlyCheck(): bool
    {
        return $this->processOnlyCheck;
    }

    public function setProcessOnlyCheck(bool $processOnlyCheck): self
    {
        $this->processOnlyCheck = $processOnlyCheck;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(?string $taxNumber): self
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getBillingAddress(): ?RegisterAddressDto
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?RegisterAddressDto $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getReceiverAddress(): ?RegisterAddressDto
    {
        return $this->receiverAddress;
    }

    public function setReceiverAddress(?RegisterAddressDto $receiverAddress): self
    {
        $this->receiverAddress = $receiverAddress;

        return $this;
    }

    public function getAgreements(): array
    {
        return $this->agreements;
    }

    public function setAgreements(array $agreements): self
    {
        $this->agreements = $agreements;

        return $this;
    }

    public function getRecaptchaToken(): ?string
    {
        return $this->recaptchaToken;
    }

    public function setRecaptchaToken(?string $recaptchaToken): self
    {
        $this->recaptchaToken = $recaptchaToken;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(?string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getReceiverFirstName(): ?string
    {
        return $this->receiverFirstName;
    }

    public function setReceiverFirstName(?string $receiverFirstName): self
    {
        $this->receiverFirstName = $receiverFirstName;

        return $this;
    }

    public function getReceiverLastName(): ?string
    {
        return $this->receiverLastName;
    }

    public function setReceiverLastName(?string $receiverLastName): self
    {
        $this->receiverLastName = $receiverLastName;

        return $this;
    }
}
