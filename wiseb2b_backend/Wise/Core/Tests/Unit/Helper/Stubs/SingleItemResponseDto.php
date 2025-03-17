<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Helper\Stubs;

use Wise\Core\Dto\AbstractResponseDto;
use Wise\Core\Service\Merge\MergeType;

final class SingleItemResponseDto extends AbstractResponseDto
{
    private int $id;
    private string $name;
    /** @var list<string> $strings */
    private array $strings;
    #[MergeType(type: Phone::class, list: true)]
    /** @var list<Phone> $phones */
    private array $phones;

    private \DateTime $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStrings(): array
    {
        return $this->strings;
    }

    public function setStrings(array $strings): void
    {
        $this->strings = $strings;
    }

    public function getPhones(): array
    {
        return $this->phones;
    }

    public function setPhones(array $phones): void
    {
        $this->phones = $phones;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
