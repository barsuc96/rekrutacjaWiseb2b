<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\ApiUi\Dto\CommonPostUiApiDto;
use Wise\Core\Model\ValidatableInterface;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;

class PostReceiversDto extends CommonPostUiApiDto implements ValidatableInterface
{
    #[Assert\NotBlank(
        message: "DTO//Ta wartość nie może być pusta.",
        payload: ["constraintType" => ConstraintTypeEnum::ERROR],
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "DTO//Pole może zawierać maksymalnie {{ limit }} znaków."
    )]
    #[OA\Property(
        description: 'Nazwa odbiorcy',
        example: 'Quattro Forum',
    )]
    protected string $name;

    #[Assert\NotBlank(
        message: "DTO//Ta wartość nie może być pusta."
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "DTO//Pole może zawierać maksymalnie {{ limit }} znaków."
    )]
    #[OA\Property(
        description: 'Imię odbiorcy',
        example: 'Adam',
    )]
    protected string $firstName;

//    #[Assert\NotBlank(
//        message: "DTO//Ta wartość nie może być pusta.",
//        payload: ["constraintType" => ConstraintTypeEnum::WARNING],
//    )]
//    #[Assert\Length(
//        max: 60,
//        maxMessage: "DTO//Pole może zawierać maksymalnie {{ limit }} znaków."
//    )]
    #[OA\Property(
        description: 'Nazwisko odbiorcy',
        example: 'Kowalski',
    )]
    protected string $lastName;

    #[Assert\NotBlank(
        message: "DTO//Ta wartość nie może być pusta.",
        payload: ["constraintType" => ConstraintTypeEnum::WARNING],
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "DTO//Pole może zawierać maksymalnie {{ limit }} znaków."
    )]
     #[OA\Property(
        description: 'Adres e-mail odbiorcy',
        example: 'dkowalczyk@sente.pl',
    )]
    protected string $email;

    #[Assert\NotBlank(
        message: "DTO//Ta wartość nie może być pusta."
    )]
    #[OA\Property(
        description: 'Numer telefonu odbiorcy',
        example: '123456789',
    )]
    protected string $phone;

    protected PostAddressDto $address;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PostReceiversDto
    {
        $this->name = $name;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): PostReceiversDto
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): PostReceiversDto
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): PostReceiversDto
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): PostReceiversDto
    {
        $this->phone = $phone;
        return $this;
    }

    public function getAddress(): PostAddressDto
    {
        return $this->address;
    }

    public function setAddress(PostAddressDto $address): PostReceiversDto
    {
        $this->address = $address;
        return $this;
    }
}
