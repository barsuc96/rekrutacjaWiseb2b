<?php

declare(strict_types=1);

namespace Wise\User\Domain\User;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\Object\AddressHelper;
use Wise\Core\Model\Address;
use Wise\User\Domain\User\Events\UserHasChangedEvent;
use Wise\User\Repository\Doctrine\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\Index(columns: ['id_external'])]
#[ORM\Index(columns: ['login'])]
#[ORM\Index(columns: ['email'])]
#[ORM\UniqueConstraint(name: 'user_unique_loginx', columns: ['login'])]
#[ORM\UniqueConstraint(name: 'user_unique_login_store_idx', columns: ['login', 'store_id'])]
/**
 * email uniqueness is removed for agrotex users with duplicated emails
 */
//#[ORM\UniqueConstraint(name: 'user_unique_email_store_idx', columns: ['email', 'store_id'])]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(nullable: true)]
    protected ?string $idExternal = null;

    #[ORM\Column(nullable: true)]
    protected ?int $clientId = null;

    #[ORM\Column(nullable: true)]
    protected ?int $roleId = null;

    /**
     * Wskazanie na konto sprzedawcy pod ktorego podlega dany uzytkownik
     */
    #[ORM\Column(nullable: true)]
    protected ?int $traderId = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 60)]
    protected ?string $login = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    protected ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    protected ?DateTimeInterface $createDate = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 60, nullable: true)]
    protected ?string $firstName = null;

    #[ORM\Column(length: 60, nullable: true)]
    protected ?string $lastName = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 60, nullable: true)]
    protected ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    protected ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $salt = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    protected ?bool $mailConfirmed = null;

    #[ORM\Column(nullable: true)]
    protected ?int $storeId = null;

    protected ?Address $registerAddress = null;


    public function __construct()
    {
        $this->registerAddress = new Address();
    }

    public function getRegisterAddress(): ?Address
    {
        return $this->registerAddress;
    }

    public function setRegisterAddress(?Address $registerAddress): self
    {
        $this->registerAddress = $registerAddress;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        if($this->roleId === null){
            return [];
        }

        $roles = UserRoleEnum::getRoleName($this->roleId);
        return array_unique($roles);
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier() instead
     */
    #[Pure]
    public function getUsername(): ?string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getRoleId(): ?int
    {
        return $this->roleId;
    }

    public function setRoleId(?int $roleId): self
    {
        $this->roleId = $roleId;

        return $this;
    }

    public function getTraderId(): ?int
    {
        return $this->traderId;
    }

    public function setTraderId(?int $traderId): self
    {
        $this->traderId = $traderId;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = strtolower($login);

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

    public function getCreateDate(): ?DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(?DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = strtolower($email);

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

    public function getMailConfirmed(): ?bool
    {
        return $this->mailConfirmed;
    }

    public function setMailConfirmed(?bool $mailConfirmed): self
    {
        $this->mailConfirmed = $mailConfirmed;

        return $this;
    }

    public function getStoreId(): ?int
    {
        return $this->storeId;
    }

    public function setStoreId(?int $storeId): self
    {
        $this->storeId = $storeId;

        return $this;
    }

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new UserHasChangedEvent($this->getId()));
    }
}
